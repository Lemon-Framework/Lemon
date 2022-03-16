<?php

declare(strict_types=1);

namespace Lemon\Sessions;

use Lemon\Http\Response;

/**
 * CSRF preventing class.
 */
class Csrf
{
    /**
     * Creates new CSRF token and saves it into user's session.
     */
    public static function setToken(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $token = uniqid();
            $token = hash('sha256', $token);
            $_SESSION['csrf_token'] = $token;
        }
    }

    /**
     * Validates CSRF token by comparing the one from POST input and session.
     */
    public static function check(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['csrf_token'], $_SESSION['csrf_token'])) {
                if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    (new Response('', 400))->terminate();

                    exit;
                }
            } else {
                (new Response('', 400))->terminate();

                exit;
            }
        }
    }

    /**
     * Returns CSRF token from session.
     */
    public static function getToken(): string
    {
        return $_SESSION['csrf_token'] ?? '';
    }
}
