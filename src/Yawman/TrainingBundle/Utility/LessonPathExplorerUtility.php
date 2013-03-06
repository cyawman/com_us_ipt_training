<?php

namespace Yawman\TrainingBundle\Utility;

/**
 * Utility classed used for selecting a Lesson path
 */
class LessonPathExplorerUtility {

    /**
     * Current path
     * 
     * @var string
     */
    var $path;

    /**
     * Directory list
     * 
     * @var array
     */
    var $directories = array();

    /**
     * File list
     * 
     * @var array
     */
    var $files = array();
    
    public function fetchPath(){
        if(!$this->path){
            throw new \ErrorException('The path must be set first.');
        }
        if (is_dir($this->path)) {
            chdir($this->path); // Focus on the dir
            $handle = opendir('.');
            if ($handle) {
                while (($item = readdir($handle)) !== false) {
                    // Loop through current directory and divide files and directorys
                    if (is_dir($item)) {
                        if($item != '.' && $item != '..'){
                            $this->directories[$item] = $item;
                        }
                    } else {
                        array_push($this->files, ($item));
                    }
                }
                closedir($handle); // Close the directory handle
            } else {
                throw new \ErrorException('Directory handle could not be obtained.');
            }
        } else {
            throw new \ErrorException('Path is not a directory: ' . $this->path);
        }
    }
    
    public function getPath() {
        return $this->path;
    }

    public function setPath($path) {
        $this->path = $path;
    }
    
    public function getDirectories() {
        return $this->directories;
    }

    public function setDirectories($directories) {
        $this->directories = $directories;
    }

    public function getFiles() {
        return $this->files;
    }

    public function setFiles($files) {
        $this->files = $files;
    }
}