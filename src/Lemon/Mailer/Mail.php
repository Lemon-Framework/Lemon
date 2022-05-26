<?php

declare(strict_types=1);

namespace Lemon\Mailer;

use Lemon\Mailer\Exceptions\MailerException;
use Lemon\Templating\Template;

class Mail
{
    public string $to = '';

    public string $subject = '';

    public string $content = '';

    public Template $attachment;

    public function to(string $to): static
    {
        $this->to = $to;
        return $this;
    }

    public function subject(string $subject): static
    {
        $this->subject = $subject;
        return $this;
    }

    public function content(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function attach(Template $template): static
    {
        $this->attachment = $template;
        return $this;
    }

    public function send(): void
    {
        $headers = [];
        $message = '';
        if ($this->content) {
            $message = $this->content;
        } elseif ($this->attachment) {
            $message = (string) $this->attachment;
            $headers[] = 'Content-Type: text/html';
        } else {
            throw new MailerException('Content or attachment was not provided.');
        }

        mail($this->to, $this->subject, $message, $headers);
    }

}
