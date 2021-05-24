<?php

/*
 * 
 * Builders variables
 *
 * */

// List of supported arguments
$arg_list = [
    "type",  
];

// List of builder types
$types = [
    "project",
    "license"
];


// List of project directories
$dirs = [
    "public",
    "views",
    "routes",
    "controllers",
];

// List of project files
$files = [ 
    "public/index.php" => "https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/index.php",
    "routes/web.php" => "https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/web_routes.php"
];


// List of supported licenses
$licenses = [
    "mit" => "https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/licences/mit.txt",
    "apache" => "https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/licences/apache.txt"

];
?>
