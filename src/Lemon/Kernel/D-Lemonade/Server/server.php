<?php

// Running local production server
class Server
{
    private $arguments;
    private $directory;
    private $arg_list = [
        'port',
        'host',
    ];

    // Takes arguments
    public function __construct($arguments, $directory)
    {
        $this->arguments = $arguments;
        $this->directory = $directory;
    }

    // Runs whole server
    public function run()
    {
        if (is_file('public/index.php')) {
            echo textFormat("\n\u{1F34B} Lemon development server started!\n\n", '33');
            $command = $this->build();

            exec($command);
        } else {
            echo textFormat("Folder *public* is required to run server!\n", 31);
        }
    }

    // Parses arguments to associative array
    private function parse()
    {
        $parsed = [];
        foreach ($this->arguments as $argument) {
            if (str_contains($argument, ':')) {
                $argument = explode(':', $argument);
                $parsed[$argument[0]] = $argument[1];
            }
        }

        return $parsed;
    }

    // Builds serving command
    private function build()
    {
        $arguments = $this->parse();

        $address = $arguments['host'] ?? 'localhost';
        $port = $arguments['port'] ?? '8000';
        $dir = $this->directory.'/public/';

        return "php -S {$address}:{$port} -t {$dir}";
    }
}

// Function for command registration
function serve($arguments, $directory)
{
    $server = new Server($arguments, $directory);

    $server->run();
}
