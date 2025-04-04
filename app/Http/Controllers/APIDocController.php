<?php

namespace App\Http\Controllers;

use App\Datasource\NetworkHandler;
use DOMDocument;
use Illuminate\Support\Facades\File;

class APIDocController extends Controller
{
    public function getAPIDoc(string $serviceName)
    {
        $serviceURL =
            env("SERVICE_BROKER_URL") . "/broker/{$serviceName}" . "/" . "dev";

        $serviceNetwork = new NetworkHandler(
            "",
            $serviceURL,
            false,
            [],
            "rest",
            true
        );

        $swaggerJson = $serviceNetwork->get("/swagger.json", "", false);

        return $swaggerJson;
    }

    public function getAPIDocHTML(string $serviceName)
    {
        $swaggerURL = "/service-swagger-doc/" . $serviceName;

        return view("swagger", ["swaggerURL" => $swaggerURL]);
    }
}
