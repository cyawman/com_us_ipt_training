<?php

namespace Yawman\TrainingBundle\Service;

/**
 * Checks if a custom view script is available based on a key.
 * Use the default is a custom view script is not available.
 *
 * @author Chris Yawman
 */
class CustomViewFinder {

    /** 
     * Contains an array of available custom views.
     * This should be set from a config file and injected at the time the class is instantiated.
     * 
     * @var array
     */
    protected $customViews;
    
    /**
     * Holds the string value for the custom view script.
     * 
     * @var string
     */
    protected $view;
    
    public function __construct(array $customViews) {
        $this->customViews = $customViews;
    }
    
    /**
     * @param string $key
     * @param string $default
     * @return mixed
     */
    public function retrieveView($key, $default = null){
        if(!$this->hasView($key)){
            return $default;
        }
        return $this->getView();
    }
    
    /**
     * @param type $key
     * @return boolean
     */
    public function hasView($key){
        if(isset($this->customViews[$key]) && !empty($this->customViews[$key])){
            $this->setView($this->customViews[$key]);
            return true;
        }
        return false;
    }
    
    /**
     * @return string
     */
    public function getView() {
        return $this->view;
    }

    /**
     * @param string $view
     */
    public function setView($view) {
        $this->view = $view;
    }
    
}