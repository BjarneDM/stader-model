<?php namespace stader\model ;

require_once( __dir__ . '/class.usergroupdaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class UsersGroupsDao extends Setup
{
    private   $functions = null ;
    protected $usersgroups = [] ;
    
    function __construct ()
    {   // echo 'class GroupsDao __construct' . \PHP_EOL ;

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

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $usersGroupsIds = $this->functions->readAll( ...$args ) ;
        foreach ( $usersGroupsIds as $userGroupId )
        {
            $this->usersgroups[] = new UserGroup( (int) $userGroupId ) ;
        }
        reset( $this->usersgroups ) ;
    }

    public function count()     { return   count( $this->usersgroups ) ; }
    public function reset()     { return   reset( $this->usersgroups ) ; }
    public function prev()      { return    prev( $this->usersgroups ) ; }
    public function current()   { return current( $this->usersgroups ) ; }
    public function next()      { return    next( $this->usersgroups ) ; }
    public function end()       { return     end( $this->usersgroups ) ; }

    public function getUserGroup( int $index ) { return $this->usersgroups[ $index ] ; }
    public function getOne( int $index ) { return $this->getUserGroup( $index ) ; }

    public function getUsersGroups() { return $this->usersgroups ; }
    public function getAll() { return $this->getUsersGroups() ; }

}

?>
