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

namespace Lemon\Http\Response;

class JsonResponse implements IResponse
{
  
  /** @var mixed */
  private $data;
  
  private string $contentType;
  
  private bool $pretty;
  
  public function __construct(
    $data = [],
    string $contentType = "application/json",
    bool $pretty = false
  ) {
    $this->data = $data;
    $this->contentType = $contentType;
    $this->pretty = $pretty;
  }
  
  function send(): void
  {
    header("Content-Type: " . $this->contentType);
   
    // echo out the value
    echo json_encode($this->data, $this->pretty ? JSON_PRETTY_PRINT : 0);
    
    // kill
    // TODO: instead, use some sort of shutdown function
    die(0);
  }
}
