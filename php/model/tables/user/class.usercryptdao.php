<?php namespace stader\model ;

class UserCryptDao extends Setup
{
    private $functions = null ;
    protected $values = [] ;
    
    function __construct ()
    {   // echo 'class UserCryptDao __construct' . \PHP_EOL ;

        parent::__construct() ;

        switch ( self::$connect->getType() )
        {
            case "mysql"    : $this->functions = new UserCryptDaoPdo( self::$connect ) ; break ;
            case "pgsql"    : $this->functions = new UserCryptDaoPdo( self::$connect ) ; break ;
            case "sqlite"   : $this->functions = new UserCryptDaoPdo( self::$connect ) ; break ;
            case "xml"      : $this->functions = new UserCryptDaoXml( self::$connect ) ; break ;
            default: throw new \Exception() ;
            // var_dump( $this->functions ) ;
            $user = new UserDaoPdo( $connect ) ;
            // var_dump( $user ) ;
        } 
    }

    protected function create( Array $user ) 
        { $this->values = $this->functions->create( $user ) ; }

    public function read( ...$args )
        { $this->values = $this->functions->readOne( ...$args ) ; }

    public function readAll( ...$args )
        { return $this->functions->readAll( ...$args ) ; }

    public function dataDecrypt()
        { return $this->functions->dataDecrypt( $this->values ) ; }

    public function update( string $key , $value ) 
        {
            list ( $rowCount , $values ) = $this->functions->update( $this->values['id'] , $key , $value ) ;
            $this->values = $values ;
        return $rowCount  ; }

    public function delete() 
        {
            $rowCount = $this->functions->delete( $this->values['id'] ) ;
            $this->values = null ;
        return $rowCount ; }

    public function getData()   { return $this->values ; }
    public function getValues() { return array_values( $this->values ) ; }
    public function getKeys()   { return array_keys( $this->values ) ; }

}

?>
