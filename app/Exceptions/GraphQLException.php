<?php

namespace App\Exceptions;

use Exception;
use GraphQL\Error\ClientAware;
use GraphQL\Error\ProvidesExtensions;

class GraphQLException extends Exception implements ClientAware, ProvidesExtensions
{
    /**
     * @var @string
     */
    protected $reason;
    protected $extraData;

    public function __construct(string $message, array $extras = [])
    {
        parent::__construct($message);
        $this->extraData = $extras;
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * Returns string describing a category of the error.
     *
     * Value "graphql" is reserved for errors produced by query parsing or validation, do not use it.
     *
     * @api
     * @return string
     */
    public function getCategory(): string
    {
        return 'custom';
    }

    /**
     * Return the content that is put in the "extensions" part
     * of the returned error.
     *
     * @return array
     */
    public function getExtensions(): array
    {
        return [
            'extra_data' => $this->extraData,
        ];
    }
}
