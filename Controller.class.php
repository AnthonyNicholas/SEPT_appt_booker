<?php
/**
 * Controller Class
 *
 * Shell for the main controller class, this could be static and used
 * as a library of utilities for our web application or it could be
 * used as an object for keeping information about the current session
 * 
 */
class Controller
{

    public var $debug = true;

    public function displayLoginExample()
    {
        echo $this->debug;
    }

}
