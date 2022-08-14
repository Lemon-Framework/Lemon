# Lemon Kernel

This Unit contains Container and Application, main parts of Lemon.

## Container

Container is class that provides managing of services. Each service can be injected to other service using constructor.

### Standalone usage

```php

class Foo
{
    public function getGreeting(): string
    {
        return 'Hi';
    }
}

class Bar
{
    private Foo $foo;
    
    public function __construct(Foo $foo)
    {
        $this->foo = $foo;
    }

    public function sayHi(string $name): void
    {
        echo $this->foo->getGreeting() . ' ' . $name;
    }
}

$container = new \Lemon\Kernel\Container();
$container->addService(Foo::class);
$container->addService(Bar::class);

$container->getService(Bar::class)->sayHi('Majkel');
```

If you try to get unknown service, you'l get Exception

## Application

Application is main part of Lemon. It allows container-based service management, bootstraping, initialization, filesystem etc.
