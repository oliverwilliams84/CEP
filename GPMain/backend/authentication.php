<?php

function checkAuth(){
    $continue = true;
    if(!isset($_COOKIE["AuthToken"])) {
        $continue = false;
    }
    if(!isset($_COOKIE["ID"])) {
        $continue = false;
    }
    if($continue) {
        $authtoken = $_COOKIE["AuthToken"];
        $id = $_COOKIE["ID"];

        $datamapper = new MySQL_DataMapper(getPDO());


    } else {
        //TODO : Redirect to login
    }
}