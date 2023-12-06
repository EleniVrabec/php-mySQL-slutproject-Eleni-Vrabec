<?php
require_once "database.php";

class Model{

private static $connection = null;

function __construct() {


}
protected function getConection(){

    if(Model::$connection == null){
        Model::$connection = getDataBaseConnection();
    }
    return Model::$connection;

}


}

