<?php

// TODO ADD:
// getting via decorators
// LITERALY THE WHOLE THING

declare(strict_types=1);

namespace Lemon\Http;

class Request
{
    public $uri;

    /** Request headers */
    public $headers;

    /** Request method */
    public $method;

    /** JSON POST input */
    public $json;

    /** POST input */
    public $input;

    /** GET query */
    public $query;

    /** All input data */
    public $data;

    /** Request body */
    public $body;

    public function __construct($request_data)
    {
        foreach ($request_data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Returns value from data array.
     */
    public function __get(mixed $key): mixed
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }

        return null;
    }

    /**
     * Creates new Request instance from sent request.
     */
    public static function make(): self
    {
        return new self([
            'uri' => $_SERVER['REQUEST_URI'],
            'method' => $_SERVER['REQUEST_METHOD'],
            'headers' => getallheaders(),
            'input' => $_POST,
            'json' => json_decode(file_get_contents('php://input')),
            'body' => file_get_contents('php://input'),
        ]);
    }

    /**
     * Emulates requestwith given data.
     */
    public static function emulate(string $path, string $method): self
    {
        return new self([
            'uri' => $path,
            'method' => $method,
        ]);
    }

    public function setQuery($query): void
    {
        $this->query = $query;
    }

    /**
     * Returns json value.
     */
    public function json(mixed $key): mixed
    {
        return $this->json[$key];
    }

    /**
     * Returns POST input value.
     */
    public function input(mixed $key): string
    {
        return $this->input[$key];
    }

    /**
     * Returns GET query value.
     */
    public function query(): string
    {
        return $this->query;
    }

    /**
     * Returns value from header.
     */
    public function header(mixed $name): string
    {
        return $this->headers[$name];
    }
}
