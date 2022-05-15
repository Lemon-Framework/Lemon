<?php

declare(strict_types=1);

namespace Lemon\Http;

use Exception;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\JsonResponse;
use Lemon\Kernel\Lifecycle;

class ResponseFactory
{
    public function __construct(
        private Lifecycle $lifecycle
    ) {
        
    }

    public function make(callable $action, array $params): Response
    {
        $output = $this->lifecycle->call($action, $params);
        return $this->resolve($output);
    }

    public function resolve(mixed $data): Response
    {
        if (is_scalar($data) || is_null($data)) {
            return new HtmlResponse($data);
        }

        if (is_array($data)) {
            return new JsonResponse($data);
        }

        if ($data instanceof Response) {
            return $data;
        }

        if ($data instanceof Jsonable) {
            return new JsonResponse($data->toJson());
        }

        throw new Exception('Class '.$data::class.' can\'t be resolved as response');
    }
}
