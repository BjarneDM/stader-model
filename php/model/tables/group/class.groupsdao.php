<?php namespace stader\model ;

class UGroupsDao extends Setup
{
    private   $functions = null ;
    protected $groups = [] ;
    
    function __construct ()
    {   // echo 'class GroupsDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new GroupDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new GroupDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new GroupDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new GroupDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 
    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $groupIds = $this->functions->readAll( ...$args ) ;
        foreach ( $groupIds as $groupId )
        {
            $this->groups[] = new Group( (int) $groupId ) ;
        }
        reset( $this->groups ) ;
    }

    public function count()     { return   count( $this->groups ) ; }
    public function reset()     { return   reset( $this->groups ) ; }
    public function prev()      { return    prev( $this->groups ) ; }
    public function current()   { return current( $this->groups ) ; }
    public function next()      { return    next( $this->groups ) ; }
    public function end()       { return     end( $this->groups ) ; }

    public function getGroup( int $index ) { return $this->groups[ $index ] ; }
    public function getOne( int $index ) { return $this->getGroup( $index ) ; }

    public function getGroups() { return $this->groups ; }
    public function getAll() { return $this->getGroups() ; }

}

?>
