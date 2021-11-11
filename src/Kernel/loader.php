<?php
/**
 * Loads all files from specific folder
 * Inspired by loader from CoolFido
 *
 * @param String $dir
 *
 */
 function loader(String $dir)
 {
     foreach (scandir($dir) as $file)
     {
         $path = $dir.DIRECTORY_SEPARATOR.$file;
         if (in_array($file, [".", ".."]))
             continue;

         if (preg_match("/\.php$/", $file))
             require_once($path);
         
         if (is_dir($path))
             loader($path);
     }
 }


