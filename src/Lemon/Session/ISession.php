<?php declare(strict_types=1);

namespace Lemon\Session;


interface ISession
{
  
  function start(): void;
  
  function hasStarted(): bool;
  
  function setName(string $name): void;
  
  function getId(): string;
  
}
