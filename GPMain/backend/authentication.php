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

        $array = $datamapper->getAuthTokenByID($id);

        foreach ($array as $item) {
            if (!$item["isExpired"]){
                if($item["authToken"] == $authtoken){
                    return true;
                }
            }
        }
        unset($item);
    }

    header('Location: '. LOGIN);

    return false;
}