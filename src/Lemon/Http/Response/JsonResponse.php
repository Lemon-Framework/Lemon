<?php declare(strict_types=1);

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
