<?php declare(strict_types=1);

namespace Lemon\Http\Response;

interface IResponse
{

  function send(): void;
  
}
