<?php namespace stader\model ;

require_once( __dir__ . '/class.typebytedaopdo.php' ) ;
require_once( dirname( __file__ , 2 ) . '/connect/class.setup.php' ) ;

class TypeBytesDao extends Setup
{
    private   $functions = null ;
    protected $typebytes = [] ;
    
    function __construct ()
    {   // echo 'class TypeBytesDao extends Setup __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new TypeByteDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new TypeByteDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new TypeByteDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new TypeByteDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
        } 

    }

    protected function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $typebyteIds = $this->functions->readAll( ...$args ) ;
        foreach ( $typebyteIds as $typebyteId )
        {
            $this->typebytes[] = new TypeByte( (int) $typebyteId ) ;
        }
        reset( $this->typebytes ) ;
    }

    public function count()     { return   count( $this->typebytes ) ; }
    public function reset()     { return   reset( $this->typebytes ) ; }
    public function prev()      { return    prev( $this->typebytes ) ; }
    public function current()   { return current( $this->typebytes ) ; }
    public function next()      { return    next( $this->typebytes ) ; }
    public function end()       { return     end( $this->typebytes ) ; }

    public function getTypeByte( int $index ) { return $this->typebytes[ $index ] ; }
    public function getOne( int $index ) { return $this->getTypeByte( $index ) ; }

    public function getTypeBytes() { return $this->typebytes ; }
    public function getAll() { return $this->getTypeBytes() ; }

}

?>
