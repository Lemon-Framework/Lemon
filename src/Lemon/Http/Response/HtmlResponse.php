<?php declare(strict_types=1);

namespace Lemon\Http\Response;

class HtmlResponse extends TextResponse
{
  
  public function __construct(string $data = "") {
   parent::__construct($data, "text/html");
  }
  
}
