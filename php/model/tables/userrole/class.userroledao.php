<?php namespace stader\model ;

require_once( __dir__ . '/class.userroledaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/role/class.role.php' ) ;
require_once( dirname( __file__ , 2 ) . '/user/class.user.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class UserRoleDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class 'RoleDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new UserRoleDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new UserRoleDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new UserRoleDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new UserRoleDaoXml( self::$connect ) ; break ;
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
            if ( in_array( $key , [ 'role' ] ) )
            {
                $role = new Role( $key , $value ) ;
                $ids['role_id'] = $role->getData()['role_id'] ;
            }
            if ( in_array( $key , [ 'role_id' ] ) )
            {
                $role = new Role( $value ) ;
                $ids['role_id'] = $role->getData()['role_id'] ;
            }
            unset( $user , $role) ;
        } unset( $key , $value ) ;

    return $ids ; }

    protected function read( ...$args )
        { $this->values = $this->functions->readOne( ...$args ) ; }

    protected function update( string $key , $value ) 
        {
            $oldValue = $this->values[ $key ] ;
            $rowCount = $this->functions->update( $this->values['user_role_id'] , $key , $value ) ;
        return [ $rowCount , $oldValue ] ; }

    public function delete()
        {
            $rowCount = $this->functions->delete( $this->values['user_role_id'] ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
