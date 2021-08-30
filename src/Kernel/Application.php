<?php

namespace Lemon\Kernel;

use Lemon\Views\ViewCompiler;

/**
 * Class for loading and booting whole app
 *
 * @param String $booter_dir
 *
 */
class Application
{

    /**
     * Folders that will be loaded
     */
    public $load_folders;

    /**
     * Views storage folder
     */
    public $view_folder;

    /**
     * Directory of booting file
     */
    public $booter_dir;

    public function __construct(String $booter_dir)
    {
        $this->load_folders = [];
        $this->view_folder = "views";
        $this->booter_dir = $booter_dir;
    }

    /**
     * Sets folders to be loaded
     *
     * @param String ...$folders
     *
     */
    public function load(String ...$folders)
    {
        $this->load_folders = $folders;
    }

    /**
     * Sets view storage folder
     *
     * @param String $folder
     *
     */
    public function views(String $folder)
    {
        $this->view_folder = $folder;
    }

    /**
     * Runs whole application
     */
    public function boot()
    {
        $sep = DIRECTORY_SEPARATOR;
        foreach ($this->load_folders as $folder)
            loader("{$this->booter_dir}{$sep}{$folder}");

        ViewCompiler::setDirectory("{$this->booter_dir}{$sep}{$this->view_folder}");
        
        if (in_array("SERVER_NAME", $_SERVER))
           \Route::execute();
    }
}

?>
