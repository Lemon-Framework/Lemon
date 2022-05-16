<?php

declare(strict_types=1);

namespace Lemon\Http;

use Exception;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\JsonResponse;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Kernel\Lifecycle;
use Lemon\Templating\Factory as Templating;
use Lemon\Templating\Template;

class ResponseFactory
{
    private array $handlers = [];

    public function __construct(
        private Templating $templating,
        private Lifecycle $lifecycle
    ) {
    }

    public function make(callable $action, array $params = []): Response
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

        if ($data instanceof Template) {
            return new TemplateResponse($data);
        }

        throw new Exception('Class '.$data::class.' can\'t be resolved as response');
    }

    /**
     * Returns response of 400-500 http status codes.
     */
    public function error(int $code): Response
    {
        if (!isset(Response::ERROR_STATUS_CODES[$code])) {
            throw new  Exception('Status code '.$code.' is not any error status code');
        }

        if (isset($this->handlers[$code])) {
            return $this->make($this->handlers[$code]);
        }

//        if (is_file($this->templating->getRawPath("errors.{$code}"))) {
//            return new TemplateResponse($this->templating->make("errors.{$code}"), $code);
//        }

        static $s = DIRECTORY_SEPARATOR;
        $path = __DIR__.$s.'templates'.$s.'error.phtml';

        return new TemplateResponse(new Template(
            $path,
            $path,
            ['code' => $code, 'message' => Response::ERROR_STATUS_CODES[$code]]
        ), $code);
    }

    public function raise(int $code): Response
    {
        return $this->error($code);
    }

    public function handle(int $code, callable $action): static
    {
        $this->handlers[$code] = $action;
        return $this;
    }
}
