<?php declare(strict_types=1);

/*
 * Lemon - dead simple PHP framework
 * Copyright (c) 2021 TENMAJKL and Contributors
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Lemon\Kernel;

use Closure;
use Lemon\Lifecycle\Response;
use Lemon\Protection\Csrf;
use Lemon\Session\CookieSession;
use Lemon\Session\ISession;
use Lemon\Exceptions\InvalidArgumentException;
use Lemon\Support\Strings;
use Lemon\Templating\ViewCompiler;

class App
{
  
  private ISession $session;
  
  private Csrf $csrf;
  
  private ViewCompiler $viewCompiler;
  
  private Response $response;
  
  public function __construct(array $options = [])
  {
    if (!is_array($options))
      throw new InvalidArgumentException("Expected type array for options, got '$options' " . gettype($options));
    
    $sessionClass = array_key_exists("sessionClass", $options) ? $options["sessionClass"] : CookieSession::class;
    
    $this->session = new $sessionClass();
    $this->csrf = new Csrf();
    // TODO: check for null
    $this->viewCompiler = new ViewCompiler($options["views"]);
    $this->response = new Response($this);
  }
  
  public function boot(Closure $cb = null): self {
    $this->session->start();
    
    if (isset($cb)) {
      $cb($this);
    }
    
    // TODO: Route, execute route
    return $this;
  }
  
  /**
   * @return ISession
   */
  public function getSession()
  {
    return $this->session;
  }
  
  /**
   * @return Csrf
   */
  public function getCsrf(): Csrf
  {
    return $this->csrf;
  }
  
  /**
   * @return ViewCompiler
   */
  public function getViewCompiler(): ViewCompiler
  {
    return $this->viewCompiler;
  }
  
  /**
   * @return Response
   */
  public function getResponse(): Response
  {
    return $this->response;
  }
  
}
