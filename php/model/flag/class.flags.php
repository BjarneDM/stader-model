<?php namespace stader\model ;

require_once( __dir__ . '/class.flagsdao.php' ) ;

class Flags extends FlagsDao
{
    private $allowedKeys = [ 'flag' , 'note' ] ;

    function __construct ( ...$args )
    {   // echo 'class Flag extends FlagDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'null' 
         *      hent alle flags
         *      $testFlag = new Flags() ;
         */
        if ( ! isset( $args[0] ) ) { $args = [] ; $args[0] = null ; }
        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->readAll() ;
                break ;
           default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null]" ) ;
                break ;
        }
    }

}

?>
