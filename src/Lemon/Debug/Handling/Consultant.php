<?php

declare(strict_types=1);

namespace Lemon\Debug\Handling;

use Lemon\Cache\Exceptions\CacheException;
use Lemon\Cache\Exceptions\InvalidArgumentException;
use Lemon\Config\Exceptions\ConfigException;
use Lemon\Debug\Exceptions\DebugerException;
use Lemon\Http\Exceptions\CookieException;
use Lemon\Http\Exceptions\SessionException;
use Lemon\Kernel\Exceptions\ContainerException;
use Lemon\Kernel\Exceptions\NotFoundException;
use Lemon\Routing\Exceptions\RouteException;
use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Exceptions\SyntaxException;
use Lemon\Templating\Exceptions\TemplateException;
use Lemon\Terminal\Exceptions\CommandException;
use Lemon\Terminal\Exceptions\HtmlException;
use Lemon\Terminal\Exceptions\IOException;
use Lemon\Translating\Exceptions\TranslatorException;
use Lemon\Validation\Exceptions\ValidatorException;

class Consultant
{
    public array $signatures = [
        'Call to undefined function (\w+?)\(\)' => 'function',
        'Call to undefined method ([\w\\\\]+?)::(\w+?)\(\)' => 'method',
        'Undefined property: ([\w\\\\]+?)::\$(\w+?)' => 'property',
    ];

    public array $classes = [
        CacheException::class => 'digging_deeper/caching',
        InvalidArgumentException::class => 'digging_deeper/caching',
        ConfigException::class => 'getting_started/config',
        DebugerException::class => 'getting_started/debugging',
        CookieException::class => 'getting_started/cookies',
        SessionException::class => 'getting_started/session',
        ContainerException::class => 'digging_deeper/lifecycle',
        NotFoundException::class => 'digging_deeper/lifecycle',
        RouteException::class => 'getting_started/routing',
        CompilerException::class => 'getting_started/templates',
        SyntaxException::class => 'getting_started/templates',
        TemplateException::class => 'getting_started/templates',
        CommandException::class => 'digging_deeper/terminal_in_depth',
        HtmlException::class => 'digging_deeper/terminal_in_depth',
        IOException::class => 'digging_deeper/terminal_in_depth',
        TranslatorException::class => 'digging_deeper/translating',
        ValidatorException::class => 'getting_started/validation',
    ];

    public function giveAdvice(string $class, string $message): array
    {
        $handler = $this->findHandler($message);
        $hints = [];
        if (!empty($handler)) {
            $hints = $this->{$handler[0]}($handler[1]);
        }

        if (!isset($this->classes[$class])) {
            return $hints;
        }

        $docs = $this->classes[$class];
        $hints[] = 'Try reading the <a href="https://lemon-framework.github.io/docs/'.$docs.'.html">documentation</a>';

        return $hints;
    }

    public function findHandler(string $message): array
    {
        foreach ($this->signatures as $signature => $method) {
            if (preg_match('/^'.$signature.'$/', $message, $matches)) {
                return ['handle'.ucfirst($method), $matches];
            }
        }

        return [];
    }

    public function bestMatch(array $haystack, string $needle): ?string
    {
        $best = strlen($needle);
        $best_value = null;
        foreach ($haystack as $item) {
            if (($distance = levenshtein($item, $needle)) < $best) {
                $best = $distance;
                $best_value = $item;
            }
        }

        return $best_value;
    }

    public function handleFunction(array $matches): array
    {
        $functions = array_merge(get_defined_functions()['internal'], get_defined_functions()['user']);
        $match = $this->bestMatch($functions, $matches[1]);

        return [
            $match ? ('Did you mean '.$match.'?') : 'Function was propably not loaded. Try checking your loader',
        ];
    }

    public function handleMethod(array $matches): array
    {
        $methods = get_class_methods($matches[1]);
        $match = $this->bestMatch($methods, $matches[2]);

        return [
            $match ? ('Did you mean '.$match.'?') : '',
        ];
    }

    public function handleProperty(array $matches): array
    {
        $properties = array_keys(get_class_vars($matches[1])); // TODO difference between public and private
        $match = $this->bestMatch($properties, $matches[2]);

        return [
            $match ? ('Did you mean $'.$match.'?') : '',
        ];
    }
}
