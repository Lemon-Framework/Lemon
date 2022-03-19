<?php

declare(strict_types=1);

use Lemon\Kernel\Lifecycle;
use Lemon\Support\Filesystem;
use Lemon\Support\Types\Arr;

// TODO remove, switch to Lifecycle::init() since it makes more sense

/*
| -------------------------------------------------------------------------------------
| Lemon init
| -------------------------------------------------------------------------------------
| This file (if not disabled) initializes whole lifecycle for the Lemon framework.
| Produces side effects.
| For bigger apps its recommended to do it by own. See <dokumentace ok TODO>
|
*/

return;
// DEPRECATED
// TODO SWITCH TO LIFECYCLE::INIT
// NEZ MI PRASKNE CEVKA

// If vendor/autoload.php is loaded in console context or with constant LEMON_NO_INIT
if (defined('LEMON_NO_INIT')) {
    return;
}

// If lemon mode is not defined nor is web, it won't initialize
if (! defined('LEMON_MODE')) {
    define(
        'LEMON_MODE',
        isset($_SERVER['REQUEST_METHOD']) ? 'web' : 'terminal'
    );
}

$dir = Filesystem::parent($_SERVER['DOCUMENT_ROOT']);

if (LEMON_MODE === 'web') {
    if (is_file($file = $dir.'/../maintenance.php')) {
        require $file;
    }
}

// If lemon debug is not defined, it will set it to false, so debug mode must be explicitly enabled
if (! defined('LEMON_DEBUG')) {
    define('LEMON_DEBUG', false);
}

/*
| -------------------------------------------------------
| The Lemon Lifecycle
| -------------------------------------------------------
| Lemon lifecycle is main class of Lemon.
| It loads components (units) and keeps app context.
|
*/

// Using server variable DOCUMENT_ROOT we can get the folder of our entry point and manual setting is not needed
$app = new Lifecycle($dir->content);

// Loading Lemon Zests which provide static layer over Units
$app->loadZests();

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

if (LEMON_MODE !== 'web') {
    return;
}

register_shutdown_function(static function () use ($app): void {
    if (http_response_code() >= 500) {
        return;
    }
    $app->boot();
    if (Arr::contains(scandir($app->directory), 'composer.json')) { // At this point we know that the user is running Lemon directly in the same folder as he initialized app
        echo '
<div style="
    background-color: #cc241d;
    color: #282828;
    padding: 1.5rem;
    width: 50%;
    position: fixed;
    top: 0px;
    right: 0px;
    margin: 1rem;
    filter: drop-shadow(0 20px 13px rgb(0 0 0 / 0.03)) drop-shadow(0 8px 5px rgb(0 0 0 / 0.08));
">
    Your app is being runned in the same folder as you have your composer.json and other files, which is dangerous! Please consider creating <strong>public</strong> folder with your index.php
</div>
';
    } // TODO chose between this and warning
});
