<?php

declare(strict_types=1);

$scheme = $_SERVER['REQUEST_SCHEME']
          ?? (!empty($_SERVER['HTTPS']) && 'off' != $_SERVER['HTTPS'])
             ? 'https'
             : 'http'
;
