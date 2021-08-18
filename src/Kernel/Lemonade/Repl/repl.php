<?php

class Repl
{
    /**
     * Last command part
     */
    private $last_command;

    /**
     * Command input cursor
     */
    private $cursor;

    /**
     * Parses command, if its condition, calls statements method
     */
    private function command()
    {
        $command = readline($this->cursor);

        if (preg_match("/{\$/", $command))
        {
            $this->last_command .= $command;
            $this->statements();
        }

        if (preg_match("/;$/", $command))
        {
            try
            {
                eval($this->last_command . $command);
            }
            catch(Throwable $e)
            {
                echo "\033[31m{$e->getMessage()}\033[0m";
            }
            echo "\n";
            $this->last_command = "";
            $this->cursor = "--> ";
        }

        else if ($command !== "")
        {
            $this->last_command .= $command;
            $this->cursor = "--- ";
        }

        $this->command();
    }

    /**
     * Parses more complicated statements
     */
    private function statements()
    {
        $command = readline("--- ");
        if (preg_match("/}$/", $command))
        {
            try
            {
                eval($this->last_command . $command);
            }
            catch(Throwable $e)
            {
                echo "\033[31m{$e->getMessage()}\033[0m";
            }
            echo "\n";
            $this->last_command = "";
            $this->cursor = "--> ";
            $this->command();
        }
        else
            $this->last_command .= $command;

        $this->statements();

    }

    /**
     * Runs whole repl
     */
    public function run($directory)
    {
        readline_completion_function(function() {
            $text = end(explode(" ", readline_info()["line_buffer"]));
            $functions = get_defined_functions();
            $constants = array_keys(get_defined_constants());
            $variables = array_keys(get_defined_vars());

            $symbols = explode("@#$~^&*{}[]()", "");

            $text = str_replace($symbols, "", $text);

            $functions = array_merge($functions["internal"], $functions["user"]);

            $all = array_merge($functions, $constants, $variables);

            return preg_grep("/^{$text}/", $all);

        });

        echo "\033[33mLemon repl started, exit with ctrl-c\033[0m\n";

        $dir = $directory . DIRECTORY_SEPARATOR . "app";

        if (is_dir($dir))
          loader($dir);

        $this->last_command = "";
        $this->cursor = "--> ";

        $this->command();
    }
}

/**
 * Helping function
 */
function repl($arguments, $directory)
{
    $repl = new Repl();
    $repl->run($directory);
}

?>
