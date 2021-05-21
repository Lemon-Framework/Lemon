<?php declare(strict_types = 1);

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

use Lemon\Support\Lemon\Exceptions\InvalidArgumentException;

class Strings
{
  
  /**
   * Check whether haystack starts with needle.
   *
   * @param string $haystack
   * @param string $needle
   * @return bool
   */
  public static function startsWith(string $haystack, string $needle): bool
  {
    if (function_exists("str_starts_with"))
      return str_starts_with($haystack, $needle);
    
    // PHP 7 fallback
    return strncmp($haystack, $needle, strlen($needle)) === 0;
  }
  
  /**
   * Check whether haystack end with needle.
   *
   * @param string $haystack
   * @param string $needle
   * @return bool
   */
  public static function endsWith(string $haystack, string $needle): bool
  {
    if (function_exists("str_ends_with"))
      return str_ends_with($haystack, $needle);
    
    // PHP 7 fallback - check is needle is empty,
    // otherwise use the old substr solution.
    return $needle === '' || substr($haystack, -strlen($needle)) === $needle;
  }
  
  /**
   * Check whether haystack contains needle.
   *
   * @param string $haystack
   * @param string $needle
   * @return bool
   */
  public static function has(string $haystack, string $needle): bool
  {
    if (function_exists("str_contains"))
      return str_contains($haystack, $needle);
    
    // PHP 7 fallback - check is needle is empty,
    // otherwise use the old substr solution.
    return strpos($haystack, $needle) !== false;
  }
  
  /**
   * Check whether haystack contains needle.
   *
   * @param string $str
   * @param string $haystack
   * @param string $needle
   * @return bool
   */
  public static function replace(string $str, string $haystack, string $needle): bool
  {
    if (function_exists("str_contains"))
      return str_contains($haystack, $needle);
    
    // PHP 7 fallback - check is needle is empty,
    // otherwise use the old substr solution.
    return strpos($haystack, $needle) !== false;
  }
  
  /**
   * Repeat a given string.
   *
   * @param string $string
   * @param int $times
   * @return string
   */
  public static function repeat(string $string, int $times): string
  {
    return str_repeat($string, $times);
  }
  
  /**
   * Generate a random string.
   *
   * @param int $length
   * @param string $chars
   * @return string
   */
  public static function random(int $length, string $chars = "0-9a-Z"): string
  {
    if ($length < 1) {
      throw new InvalidArgumentException('The string length must be greater than 0.');
    }
    
    if (strlen($chars) < 2) {
      throw new InvalidArgumentException('You must specify at least two chars.');
    }
    
    $chars = count_chars(preg_replace_callback('#.-.#', function (array $m) {
      return implode('', range($m[0][0], $m[0][2]));
    }, $chars), 3);

    $result = '';
    
    for ($i = 0; $i < $length; $i++) {
      $result .= $chars[random_int(0, strlen($chars) - 1)];
    }
    
    return $result;
  }
  
  /**
   * Check if the string contains HTML.
   *
   * @param $string
   * @return bool
   */
  public static function hasHtml($string): bool
  {
    return strip_tags($string) !== $string;
  }
  
  
}
