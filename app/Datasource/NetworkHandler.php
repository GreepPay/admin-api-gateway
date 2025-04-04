<?php

namespace App\Datasource;

use App\Exceptions\GraphQLException;
use App\Jobs\UpdateCache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Client\Response;

class NetworkHandler
{
    /**
     * @var string The name of the service.
     */
    protected string $service;

    /**
     * @var string The URL of the service.
     */
    protected string $service_url;

    /**
     * @var \Illuminate\Http\Client\PendingRequest The HTTP client instance.
     */
    protected $httpClient;

    /**
     * @var bool Whether to use the default cache.
     */
    protected bool $defaultCache;

    /**
     * @var string The API type (rest or graphql).
     */
    protected string $apiType;

    /**
     * @var bool Whether to ignore the service path.
     */
    protected bool $ignoreService;

    /**
     * Constructor.
     *
     * @param string $service The name of the service.
     * @param string $service_url The base URL of the service.
     * @param bool $defaultCache Whether to use the default cache.
     * @param array $headers An array of headers to include in the HTTP client.
     * @param string $apiType The API type (rest or graphql).
     * @param bool $ignoreService Whether to ignore the service path.
     */
    public function __construct(
        string $service,
        string $service_url,
        bool $defaultCache = true,
        array $headers = [],
        string $apiType = "rest",
        bool $ignoreService = false
    ) {
        $this->service = $service;
        if ($ignoreService == false) {
            $this->service_url = $service_url . "/" . $this->service;
        } else {
            $this->service_url = $service_url;
        }
        $this->defaultCache = $defaultCache;
        $this->apiType = $apiType;
        $this->ignoreService = $ignoreService;
        $mainHeaders = [
            "Authorization" => "Bearer " . request()->bearerToken(),
            "secretkey" => env("BROKER_SECRET_KEY"),
        ];
        $this->httpClient = Http::withHeaders(
            array_merge($mainHeaders, $headers)
        )->timeout(100);
    }

    /**
     * Checks if two arrays of data are different.
     *
     * @param array $oldData The old data array.
     * @param array $newData The new data array.
     *
     * @return bool True if the data is different, false otherwise.
     */
    private function dataIsDifferent(array $oldData, array $newData): bool
    {
        if (!is_array($oldData) || !is_array($newData)) {
            return true;
        }

        if (count($oldData) != count($newData)) {
            return true;
        }

        foreach ($oldData as $key => $value) {
            if (isset($newData[$key])) {
                $dataValue = $value;

                if (is_array($dataValue)) {
                    if (is_array($newData[$key])) {
                        if ($this->dataIsDifferent($newData[$key], $value)) {
                            return true;
                        }
                    } else {
                        return true;
                    }
                } elseif ($newData[$key] != $value) {
                    return true;
                }
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Publishes data to Redis.
     *
     * @param string $key The key to use for the data in Redis.
     * @param array $data The data to publish.
     *
     * @return void
     */
    private function publishData(string $key, array $data): void
    {
        $message = json_encode($data);
        Redis::set($key, $message);
    }

    /**
     * Consumes data from Redis.
     *
     * @param string $key The key to retrieve the data from.
     *
     * @return array|null The data from Redis, or null if not found.
     */
    private function consumeData(string $key): ?array
    {
        $data = Redis::get($key);

        if ($data) {
            return json_decode($data, true);
        } else {
            return null;
        }
    }

    /**
     * Handles the HTTP response.
     *
     * @param Response $response The HTTP response.
     * @param string $key The cache key.
     * @param bool $cacheData Whether to cache the data.
     * @param array $requestData The request data.
     * @param array $updateTasks An array of update tasks.
     *
     * @return mixed The response data.
     *
     * @throws \Exception|\App\Exceptions\GraphQLException
     */
    private function handleResponse(
        Response $response,
        string $key = "",
        bool $cacheData = false,
        array $requestData = [],
        array $updateTasks = []
    ) {
        if (!$response->failed()) {
            if ($cacheData) {
                $this->publishData($key, $response->json());

                if (count($requestData) > 0) {
                    $this->publishData($key . "-request", $requestData);
                }
            }

            if ($this->ignoreService) {
                return $response->body();
            }

            return $response->json();
        } else {
            $errorMessage = json_decode($response->body(), true);
            if (isset($errorMessage["message"])) {
                $errorMessage = $errorMessage["message"];
            } else {
                $errorMessage = json_encode($errorMessage);
            }
            if ($this->apiType == "rest") {
                abort($response->status(), $errorMessage);
            } elseif ($this->apiType == "graphql") {
                if ($errorMessage == "null") {
                    $response->throw();
                    return null; // unreachable, but makes static analysis happy
                }

                throw new GraphQLException($errorMessage);
            }

            // Required to make static analysis happy
            return null;
        }
    }

    /**
     * Performs a GET request.
     *
     * @param string $path The path to request.
     * @param string $params The query parameters.
     * @param bool $cacheable Whether the request is cacheable.
     *
     * @return mixed The response data.
     */
    public function get(
        string $path,
        string $params = "",
        bool $cacheable = true
    ) {
        $fullUrl = $this->service_url . $path . $params;

        Log::debug($fullUrl);

        if ($this->defaultCache && $cacheable) {
            $dataFromCache = $this->consumeData($fullUrl);

            if ($dataFromCache) {
                return $dataFromCache;
            }
        }

        $response = $this->httpClient->get($fullUrl);

        return $this->handleResponse($response, $fullUrl, $cacheable);
    }

    /**
     * Performs a POST request.
     *
     * @param string $path The path to request.
     * @param array $data The data to send.
     * @param bool $cacheable Whether the request is cacheable.
     * @param array $updateTasks An array of update tasks.
     *
     * @return mixed The response data.
     */
    public function post(
        string $path,
        array $data,
        bool $cacheable = false,
        array $updateTasks = []
    ) {
        if (count($updateTasks) > 0) {
            // $cacheUpdator = new UpdateCache($updateTasks);
            // dispatch($cacheUpdator);
        }

        $fullUrl = $this->service_url . $path;
        Log::info($fullUrl);

        if ($cacheable && $this->defaultCache) {
            $requestDataFromCache = $this->consumeData(
                $fullUrl . "post-request"
            );
            $dataFromCache = $this->consumeData($fullUrl . "post");

            if (
                $dataFromCache &&
                $this->dataIsDifferent($requestDataFromCache, $data) == false
            ) {
                return $dataFromCache;
            }
        }

        $response = $this->httpClient->post($fullUrl, $data);

        return $this->handleResponse(
            $response,
            $fullUrl . "post",
            $cacheable,
            $data,
            $updateTasks
        );
    }

    /**
     * Uploads a file.
     *
     * @param string $path The path to upload the file to.
     * @param mixed $file The file to upload.
     * @param string $name The name of the file.
     *
     * @return mixed The response data.
     */
    public function uploadFile(string $path, $file, string $name)
    {
        $fullUrl = $this->service_url . $path;
        $response = $this->httpClient
            ->attach("attachment", file_get_contents($file), $name)
            ->post($fullUrl, []);

        return $this->handleResponse($response);
    }

    /**
     * Performs a PUT request.
     *
     * @param string $path The path to request.
     * @param array $data The data to send.
     * @param array $updateTasks An array of update tasks.
     *
     * @return mixed The response data.
     */
    public function put(string $path, array $data, array $updateTasks = [])
    {
        if (count($updateTasks) > 0) {
            // $cacheUpdator = new UpdateCache($updateTasks);
            // dispatch($cacheUpdator);
        }

        $fullUrl = $this->service_url . $path;

        $response = $this->httpClient->put($fullUrl, $data);

        return $this->handleResponse($response, "", false, [], $updateTasks);
    }

    /**
     * Performs a DELETE request.
     *
     * @param string $path The path to request.
     * @param array $updateTasks An array of update tasks.
     *
     * @return mixed The response data.
     */
    public function delete(string $path, array $updateTasks = [])
    {
        if (count($updateTasks) > 0) {
            // $cacheUpdator = new UpdateCache($updateTasks);
            // dispatch($cacheUpdator);
        }

        $fullUrl = $this->service_url . $path;

        $response = $this->httpClient->delete($fullUrl);

        return $this->handleResponse($response, "", false, [], $updateTasks);
    }
}
