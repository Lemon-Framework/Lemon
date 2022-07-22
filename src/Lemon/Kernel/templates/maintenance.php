<?php

declare(strict_types=1);

http_response_code(503);

echo <<<'HTML'
    <h1>503</h1>
    <h2>Service Unavailable</h2>
HTML;
