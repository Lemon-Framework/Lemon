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

namespace Lemon\Protection;

use Lemon\Support\Encoder;
use Lemon\Support\Hasher;
use Lemon\Support\Strings;

class Csrf
{
  
  const CSRF_SESSION_NAME = "csrf_token";
  
  public function __construct()
  {
  }
  
  /**
   * Checks whether the CSRF token is valid. If no token is specified
   * the form input will be used.
   *
   * @param string|null $token
   * @return bool
   */
  public function validateCsrf(string $token = null): bool
  {
    if (!isset($token))
      $token = $_POST[self::CSRF_SESSION_NAME]; // TODO: PUT, .. requests
    
    if (!$this->createCsrfToken(substr($token, 0, 12)) === $token)
      return false;
    
    return true;
  }
  
  /**
   * Returns a CSRF secret.
   *
   * @return string
   */
  private function getSecret(): string
  {
    return $_SESSION[self::CSRF_SESSION_NAME];
  }
  
  /**
   * Sets a CSRF secret.
   *
   * @param string|null $value
   */
  private function setSecret(string $value = null)
  {
    if (!$value) $value = Strings::random(16);
    $_SESSION[self::CSRF_SESSION_NAME] = $value;
  }
  
  /**
   * Creates a CSRF token.
   *
   * @param string|null $value
   * @return string
   */
  private function createCsrfToken(string $value = null): string
  {
    if (!$value) $value = Strings::random(12);
    return $value . Encoder::base64(Hasher::sha1($this->getSecret() . $value), true);
  }
  
}
