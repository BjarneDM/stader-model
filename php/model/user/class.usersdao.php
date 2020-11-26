<?php namespace stader\model ;

require_once( __dir__ . '/class.userdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class UsersDao extends Setup
{
    private   $functions = null ;
    protected $users = [] ;
    
    function __construct ()
    {   // echo 'class UserDao __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new UserDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new UserDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new UserDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new UserDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 
    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $userIds = $this->functions->readAll( ...$args ) ;
        foreach ( $userIds as $userId )
        {
            $this->users[] = new User( (int) $userId ) ;
        }
        reset( $this->users ) ;
    }

    public function count()     { return   count( $this->users ) ; }
    public function reset()     { return   reset( $this->users ) ; }
    public function prev()      { return    prev( $this->users ) ; }
    public function current()   { return current( $this->users ) ; }
    public function next()      { return    next( $this->users ) ; }
    public function end()       { return     end( $this->users ) ; }

    public function getUser( int $index ) { return $this->users[ $index ] ; }
    public function getOne( int $index ) { return $this->getUser( $index ) ; }

    public function getUsers() { return $this->users ; }
    public function getAll() { return $this->getUsers() ; }

}

?>
