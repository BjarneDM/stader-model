<?php namespace stader\model ;

abstract class DbDriver
{
    /**
    * This is the connection object returned by
    * Model::getConn()
    * @var PDO
    */

    function __construct() {}

    public function getConn() {}
    public function getType() {}

    function __destruct() {}

}

?>
