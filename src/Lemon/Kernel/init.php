<?php

use Lemon\Kernel\Lifecycle;

/*
| -------------------------------------------------------------------------------------
| Lemon init
| -------------------------------------------------------------------------------------
| This file (if not disabled) initializes whole lifecycle for the Lemon framework. 
| Produces side effects.
| For bigger apps its recommended to do it by own. See <dokumentace ok TODO>
|
*/



// If vendor/autoload.php is loaded in console context or with constant LEMON_NO_INIT
if (!isset($_SERVER['REQUEST_METHOD']) 
    || defined('LEMON_NO_INIT'))
    return;


// If lemon mode is not defined nor is web, it won't initialize
if (!defined('LEMON_MODE'))
    define('LEMON_MODE', 'web');

if (LEMON_MODE != 'web')
    return;


// If lemon debug is not defined, it will set it to false, so debug mode must be explicitly enabled
if (!defined('LEMON_DEBUG'))
    define('LEMON_DEBUG', false);


/*
| -------------------------------------------------------
| The Lemon Lifecycle
| -------------------------------------------------------
| Lemon lifecycle is main class of Lemon. 
| It loads components (units) and keeps app context.
|
*/

// Using server variable DOCUMENT_ROOT we can get the folder of our entry point and manual setting is not needed
$app = new Lifecycle($_SERVER['DOCUMENT_ROOT']);

// Loading all units, its first step because one of the units is Config
$app->loadUnits();

// Configuring debug mode
$app->config('init')->debug = LEMON_DEBUG; 

// Loading error/exception handlers
$app->loadHandler();



/*
| ------------------------------------------------------------------------------------------------------------------------
| The end
| ------------------------------------------------------------------------------------------------------------------------
| The main advantage of init is automatic booting. 
| We register shutdown gunction which gets invoked right before connection ends. If error has occured, it won't boot.
| This system is not perfect. It saves you one line, but you don't have ability to do anything after booting.
| Its more like just-works. You understand the basic concepts but then you move on and do it by your own.
|
*/
register_shutdown_function(function() use ($app) {
    if (http_response_code() >= 500) return;
    $app->boot();
});

