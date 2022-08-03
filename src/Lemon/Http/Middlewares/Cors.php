<?php

declare(strict_types=1);

namespace Lemon\Http\Middlewares;

use Lemon\Config\Config;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Http\Request;
use Lemon\Http\Responses\EmptyResponse;

/**
 * Cors.handling middleware
 * TODO less boilerplate.
 *
 * @see https://developer.mozilla.
 *
 * org/en-US/docs/Web/HTTP/CORS#access-control-allow-methods
 */
class Cors
{
    private EmptyResponse $response;

    public function handle(Config $config, Request $request): EmptyResponse
    {
        $this->response = new EmptyResponse();
        $config = $config->get('http.cors');

        $this->handleAllowedOrigins($request, $config['allowed-origins'] ?? null);
        $this->handleExposeHeaders($config['expose-headers'] ?? null);
        $this->handleMaxAge($config['max-age'] ?? null);
        $this->handleAllowedCredentials($config['allowed-credentials'] ?? null);
        $this->handleAllowedMethods($config['allowed-methods'] ?? null);
        $this->handleAllowedHeaders($config['allowed-headers'] ?? null);

        return $this->response;
    }

    private function handleAllowedOrigins(Request $request, mixed $origins): void
    {
        if (!$origins) {
            return;
        }

        if (is_string($origins)) {
            $origin = $origins;
        } elseif (is_array($origins)) {
            if (!in_array($origin = $request->header('Origin'), $origins)) {
                return;
            }
        } else {
            throw new ConfigException('Cors.allowed-origins must be array or string');
        }

        $this->response->header('Access-Control-Allow-Origin', $origin);
    }

    private function handleExposeHeaders(mixed $headers)
    {
        if (!$headers) {
            return;
        }

        if (is_array($headers)) {
            $headers = implode(', ', $headers);
        }

        if (!is_string($headers)) {
            throw new ConfigException('Cors.expose-headers must be array or string');
        }

        $this->response->header('Access-Control-Expose-Headers', $headers);
    }

    private function handleMaxAge(mixed $age)
    {
        if (!$age) {
            return;
        }

        if (!is_int($age)) {
            throw new ConfigException('Cors.max age must be int');
        }

        $this->response->header('Access-Control-Max-Age', (string) $age);
    }

    private function handleAllowedCredentials(mixed $credentials)
    {
        if (is_null($credentials)) {
            return;
        }

        if (!is_bool($credentials)) {
            throw new ConfigException('Cors.credentials must be bool');
        }

        $this->response->header('Access-Control-Allow-Credentials', $credentials ? 'true' : 'false');
    }

    private function handleAllowedMethods(mixed $methods)
    {
        if (!$methods) {
            return;
        }

        if (is_array($methods)) {
            $methods = implode(', ', $methods);
        }

        if (!is_string($methods)) {
            throw new ConfigException('Cors.methods must be array or string');
        }

        $this->response->header('Access-Control-Allow-Methods', $methods);
    }

    private function handleAllowedHeaders(mixed $headers)
    {
        if (!$headers) {
            return;
        }

        if (is_array($headers)) {
            $headers = implode(', ', $headers);
        }

        if (!is_string($headers)) {
            throw new ConfigException('Cors.allowed-headers must be array or string');
        }

        $this->response->header('Access-Control-Allow-Headers', $headers);
    }
}
