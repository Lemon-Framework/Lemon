<?php

declare(strict_types=1);

namespace Lemon\Translating\Middlewares;

use Lemon\Contracts\Http\Session;
use Lemon\Contracts\Translating\Translator;

class TranslationLocalizer
{
    public function handle(Translator $translator, Session $session)
    {
        $translator->locate($session->get('locale'));
    }
}
