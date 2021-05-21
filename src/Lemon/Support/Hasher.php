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

namespace Lemon\Support;

class Hasher
{
  
  /**
   * Hash a string using Bcrypt.
   *
   * @param string $str
   * @param int $rounds
   * @return false|string|null
   */
  public static function bcrypt(string $str, int $rounds = 10) {
    return password_hash($str, PASSWORD_BCRYPT, [ "cost" => $rounds ]);
  }
  
  /**
   * Hash a string using Argon2.
   *
   * @param string $str
   * @param bool $argon2Id
   * @return false|string|null
   */
  public static function argon2(string $str, bool $argon2Id = true) {
    return password_hash($str, $argon2Id ? PASSWORD_ARGON2ID : PASSWORD_ARGON2I);
  }
  
  /**
   * Hash a string using SHA1.
   * Not recommended for passwords.
   *
   * @param string $str
   * @param bool $binary
   * @return string
   */
  public static function sha1(string $str, bool $binary = true): string
  {
    return sha1($str, $binary);
  }
  
}
