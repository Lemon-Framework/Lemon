<?php

declare(strict_types=1);

readline_callback_handler_install('', static function (): void {
});
while (true) {
    $r = [STDIN];
    $w = null;
    $e = null;
    $n = stream_select($r, $w, $e, null);
    if ($n && in_array(STDIN, $r)) {
        $c = stream_get_contents(STDIN, 1);
        echo "Char read: {$c}\n";

        break;
    }
}
