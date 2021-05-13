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

// Routes folder
require "routing/routes.php";
require "routing/errors.php";

// Views folder
require "views/views.php";

// Utils folder
require "utils/utils.php";
require "utils/constants.php";

// Session folder
require "session/session.php";
require "session/csrf.php";

?>
