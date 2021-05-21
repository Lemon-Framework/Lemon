# Lemon Next - Develop branch

This is the develop branch for Lemon's next release - 2.0. There are 3 main goals:

- Rewrite the library, stabilize the API- Lemonade -
- Lemonade - CLI for development
- Add more features, such as database connections and authentication

The readme below is inaccurate and outdated.

:warning: Code in this branch is not ready for production!

# Lemon

Lemon is php micro framework built for simple applications.\
Latest version: 1.3.4\
Documentation: https://tenmajkl.github.io/docs.html

# Installation

Installation is provided via composer:\
`composer create-project lemon_framework/lemon:dev-master project-name`

Lemon doesn't use project generator, so you need to make files manually and run app using `php -S localhost:port`\
// Lemonade coming soon :)

# Minimal app

Here is code of simple app build only in index.php

```php    

<?php
require "/lemon/framework.php";


Route::get("/relative/(.+)/", function($var)
    {
        echo $var;
    });

Route::any("/", function()
    {
        echo "hi";
    });

Route::handler(404, function()
    {
        echo "404";
    });

Route::execute();

?>

```



