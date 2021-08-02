<?php
/**
 *
 * Loads all files from specific folder
 *
 * Built for index in public folder
 *
 *
 */
 function loader($dir)
 {
     foreach (scandir($dir) as $file)
     {
         $path = $dir.DIRECTORY_SEPARATOR.$file;
         if (in_array($file, [".", ".."]))
             continue;

         if (str_ends_with($file, ".php"))
             require_once($path);
         else if (is_dir($path))
             loader($path);
     }
 }

?>
