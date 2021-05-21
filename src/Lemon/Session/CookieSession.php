<?php declare(strict_types=1);

namespace Lemon\Session;

use Lemon\Support\Strings;

class CookieSession implements ISession
{
  private string $name;
  
  private string $id;
  
  private bool $hasStarted = false;
  
  public function __construct($name = "lemon_session")
  {
    $this->name = $name;
  }
  
  public function start(): void {
    // Init the session if it hasn't started yet.
    if (
      session_status() !== PHP_SESSION_ACTIVE ||
      !$this->hasStarted
    ) {
      $this->init();
    }
  }
  
  private function init()
  {
    session_start();
    
    session_set_cookie_params([
      "httponly" => true,
    ]);
    
    // Set the correct session name.
    if (session_name() !== $this->name) {
      session_name($this->name);
    }
  
    // Create a new Session ID if it's not set.
    if (!$this->id) {
      $this->id = Strings::random(16);
    }
  }
  
  function hasStarted(): bool
  {
    return $this->hasStarted;
  }
  
  function setName(string $name): void
  {
    session_name($name);
  }
  
  function getId(): string
  {
    return $this->id;
  }
}
