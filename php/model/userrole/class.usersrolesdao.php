<?php namespace stader\model ;

require_once( __dir__ . '/class.userroledaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class UsersRolesDao extends Setup
{
    private   $functions = null ;
    protected $usersroles = [] ;
    
    function __construct ()
    {   // echo 'class RolesDao __construct' . \PHP_EOL ;

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

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $usersRolesIds = $this->functions->readAll( ...$args ) ;
        foreach ( $usersRolesIds as $userRoleId )
        {
            $this->usersroles[] = new UserRole( (int) $userRoleId ) ;
        }
        reset( $this->usersroles ) ;
    }

    public function count()     { return   count( $this->usersroles ) ; }
    public function reset()     { return   reset( $this->usersroles ) ; }
    public function prev()      { return    prev( $this->usersroles ) ; }
    public function current()   { return current( $this->usersroles ) ; }
    public function next()      { return    next( $this->usersroles ) ; }
    public function end()       { return     end( $this->usersroles ) ; }

    public function getUserRole( int $index ) { return $this->usersroles[ $index ] ; }
    public function getOne( int $index ) { return $this->getUserRole( $index ) ; }

    public function getUsersRoles() { return $this->usersroles ; }
    public function getAll() { return $this->getUsersRoles() ; }

}

?>
