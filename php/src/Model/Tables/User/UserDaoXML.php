<?php namespace Stader\Model ;

require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class UserDaoXml implements ICrudDao
{

    public function create( Array $array ) {}

    public function read( int $id ) {}

    protected function update( string $key , var $value ) {}

    public function delete( int $id ) {}

}

?>
