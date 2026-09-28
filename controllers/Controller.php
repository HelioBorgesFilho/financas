<?php

namespace controllers;

class Controller {
    

    public function checkingRoutes($routes){

        if(count($routes) > 1){
            return false;
        }

    }
}