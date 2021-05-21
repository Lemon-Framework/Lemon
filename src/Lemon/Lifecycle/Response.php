<?php declare(strict_types=1);

namespace Lemon\Lifecycle;

use Lemon\Exceptions\ViewException;
use Lemon\Http\Response\HtmlResponse;
use Lemon\Http\Response\IResponse;
use Lemon\Http\Response\JsonResponse;
use Lemon\Http\Response\TextResponse;
use Lemon\Kernel\App;

class Response
{
  
  private App $app;
  
  public function __construct(App $app)
  {
    $this->app = $app;
  }
  
  public function send(IResponse $response) {
    $response->send();
  }
  
  public function json($json, bool $pretty = false) {
    $this->send(new JsonResponse($json, "application/json", $pretty));
  }
  
  public function text($text) {
    $this->send(new TextResponse($text));
  }
  
  public function html($html) {
    $this->send(new HtmlResponse($html));
  }
  
  /**
   * @throws ViewException
   */
  public function view(string $viewName, array $args = []) {
    // TODO: improve
    eval($this->app->getViewCompiler()->makeView($viewName, $args)->getCompiledTemplate());
  }
  
}
