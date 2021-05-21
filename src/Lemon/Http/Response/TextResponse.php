<?php declare(strict_types=1);

namespace Lemon\Http\Response;

class TextResponse implements IResponse
{
  
  private string $data;
  
  private string $contentType;
  
  public function __construct(
    string $data = "",
    string $contentType = "text/plain"
  ) {
    $this->data = $data;
    $this->contentType = $contentType;
  }
  
  function send(): void
  {
    header("Content-Type: " . $this->contentType);
   
    // echo out the value
    echo $this->data;
    
    // kill
    // TODO: instead, use some sort of shutdown function
    die(0);
  }
}
