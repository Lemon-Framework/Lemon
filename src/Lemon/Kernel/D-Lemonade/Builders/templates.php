<?php

// Builders variables

// List of supported arguments
const ARG_LIST = [
    'type',
];

// List of builder types
const TYPES = [
    'project',
    'license',
];

// List of project directories
const DIRS = [
    'public',
    'views',
    'routes',
    'app',
];

// List of project files
const FILES = [
    'public/index.php' => 'https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/index.php',
    'routes/web.php' => 'https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/web_routes.php',
    'app.php' => 'https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/booter.php',
];

// List of supported licenses
const LICENSES = [
    'mit' => 'https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/licences/mit.txt',
    'apache' => 'https://raw.githubusercontent.com/Lemon-Framework/Examples/master/templates/licences/apache.txt',
];
