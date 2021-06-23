<?php

/*
 *
 * File to merge all files
 *
 * Lemon framework built by TEN MAJKL thanks to:
 *  -CoolFido
 *  -StackOverflow guy
 *  -YouTube guy
 *
 *
 * */

// Http folder
require "http/routing/Route.php";
require "http/request.php";

// Views folder
require "views/views.php";

// Utils folder
require "utils/utils.php";
require "utils/constants.php";

// Session folder
require "session/session.php";
require "session/csrf.php";

?>
