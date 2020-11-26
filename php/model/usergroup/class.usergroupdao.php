<?php namespace stader\model ;

require_once( __dir__ . '/class.usergroupdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/group/class.group.php' ) ;
require_once( dirname( __file__ , 2 ) . '/user/class.user.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class UserGroupDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class 'GroupDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new UserGroupDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new UserGroupDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new UserGroupDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new UserGroupDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function create( Array $array ) 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;

        $ids = $this->convertKeys( $array ) ;

        return $this->functions->create( $ids ) ;
    }

    private function convertKeys( Array $array )
    {
        $ids = [] ;
        foreach ( $array as $key => $value )
        {
            if ( in_array( $key , [ 'username' , 'email' ] ) )
            {
                $user  = new  User( $key , $value ) ;
                $ids['user_id'] = $user->getData()['user_id'] ;
            }
            if ( in_array( $key , [ 'user_id' ] ) )
            {
                $user  = new  User( $value ) ;
                $ids['user_id'] = $user->getData()['user_id'] ;
            }
            if ( in_array( $key , [ 'name' ] ) )
            {
                $group = new Group( $key , $value ) ;
                $ids['group_id'] = $group->getData()['group_id'] ;
            }
            if ( in_array( $key , [ 'group_id' ] ) )
            {
                $group = new Group( $value ) ;
                $ids['group_id'] = $group->getData()['group_id'] ;
            }
            unset( $user , $group) ;
        } unset( $key , $value ) ;
 
    return $ids ; }

    protected function read( ...$args )
        { $this->values = $this->functions->readOne( ...$args ) ; }

    protected function update( string $key , $value ) 
        {
            $oldValue = $this->values[ $key ] ;
            $rowCount = $this->functions->update( $this->values['user_group_id'] , $key , $value ) ;
        return [ $rowCount , $oldValue ] ; }

    public function delete()
        {
            $rowCount = $this->functions->delete( $this->values['user_group_id'] ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
