<?php namespace stader\model ;

require_once( __dir__ . '/class.roledaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class RolesDao extends Setup
{
    private   $functions = null ;
    protected $roles = [] ;
    
    function __construct ()
    {   // echo 'class RolesDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new RoleDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new RoleDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new RoleDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new RoleDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $roleIds = $this->functions->readAll( ...$args ) ;
        foreach ( $roleIds as $roleId )
        {
            $this->roles[] = new Role( (int) $roleId ) ;
        }
        reset( $this->roles ) ;
    }

    public function count()     { return   count( $this->roles ) ; }
    public function reset()     { return   reset( $this->roles ) ; }
    public function prev()      { return    prev( $this->roles ) ; }
    public function current()   { return current( $this->roles ) ; }
    public function next()      { return    next( $this->roles ) ; }
    public function end()       { return     end( $this->roles ) ; }

    public function getRole( int $index ) { return $this->roles[ $index ] ; }
    public function getOne( int $index ) { return $this->getRole( $index ) ; }

    public function getRoles() { return $this->roles ; }
    public function getAll() { return $this->getRoles() ; }

}

?>
