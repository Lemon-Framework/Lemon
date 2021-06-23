# Lemon

Lemon is php micro framework built for simple applications.\
Latest version: 2.3.0\
Documentation: https://lemon-framework.github.io//docs.html

# Installation

Installation is provided via composer:\
`composer create-project lemon_framework/lemon:dev-master project-name`

If you don't  have composer, you can use curl:\
`curl -s "https://raw.githubusercontent.com/Lemon-Framework/Examples/master/downloader" | bash`

If you want to build starting app type `php lemonade build type:project`

# Minimal app

Here is code of simple app build only in index.php

```php    

<?php
require "/lemon/framework.php";
use Lemon\Routing\Route;

Route::get("/relative/{var}/", function($var)
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
