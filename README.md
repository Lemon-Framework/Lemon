# Lemon

Lemon is php micro framework built for simple applications.

# Installation

Lemon is not in Composer, etc. So here is installation:

* Download folder `Lemon` and make directory like this:

```
/project_name
    /lemon
    /index.php #here starts your app

```

# Minimal app

Here is code of simple app build only in index.php

```php    

<?php
include "/lemon/framework.php";


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

To run your app locally, just type `php -s localhost:port`

// I am working on better docs.
