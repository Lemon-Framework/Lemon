<?php

declare(strict_types=1);

namespace Lemon\Http;

use Exception;
use InvalidArgumentException;
use Lemon\Http\Responses\EmptyResponse;
use Lemon\Http\Responses\HtmlResponse;
use Lemon\Http\Responses\JsonResponse;
use Lemon\Http\Responses\TemplateResponse;
use Lemon\Kernel\Application;
use Lemon\Templating\Factory as Templating;
use Lemon\Templating\Template;

class ResponseFactory
{
    private array $handlers = [];

    public function __construct(
        private Templating $templating,
        private Application $lifecycle
    ) {
    }

    /**
     * Creates new response out of given callable.
     */
    public function make(callable $action, array $params = []): Response
    {
        $output = $this->lifecycle->call($action, $params);

        return $this->resolve($output);
    }

    /**
     * Returns response depending on given data.
     */
    public function resolve(mixed $data): Response
    {
        if (is_null($data)) {
            return new EmptyResponse();
        }

        if (is_scalar($data)) {
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
     * Returns response for 400-500 http status codes.
     */
    public function error(int $code): Response
    {
        if (!isset(Response::ERROR_STATUS_CODES[$code])) {
            throw new InvalidArgumentException('Status code '.$code.' is not error status code');
        }

        if (isset($this->handlers[$code])) {
            return $this->make($this->handlers[$code]);
        }

        if ($this->templating->exist("errors.{$code}")) {
            return new TemplateResponse($this->templating->make("errors.{$code}"), $code);
        }

        static $s = DIRECTORY_SEPARATOR;
        $path = __DIR__.$s.'templates'.$s.'error.phtml';

        return new TemplateResponse(new Template(
            $path,
            $path,
            ['code' => $code]
        ), $code);
    }

    /**
     * Returns response for 400-500 http status codes.
     */
    public function raise(int $code): Response
    {
        return $this->error($code);
    }

    /**
     * Registers custom handler for given status code.
     */
    public function handle(int $code, callable $action): static
    {
        $this->handlers[$code] = $action;

        return $this;
    }
}
