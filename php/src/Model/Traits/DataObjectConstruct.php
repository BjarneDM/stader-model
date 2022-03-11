<?php namespace Stader\Model\Traits ;

trait DataObjectConstruct
{

/*
__construct tager sig af C og R delene af CRUD alt afhængig af værdien af ...$args 
...$args er de facto en overload af __construct, 
men da overload af funktioner ikke explicit ekisterer i PHP
må dette simuleres m/ brugen af ...$args efterfulgt af en analyse af ...$args 

I dette tilfælde haves : 
Create :
    new Object( Array $allValues ) , hvor array_keys( $allValues ) === array_keys( self::$allowedKeys )

Read : 
    new Object( int $id )
    new Object( Array  $keys , Array $values )
    new Object( string $key  , Multi $value  )
 */

    function __construct ( ...$args )
    {   // echo "class ". self::$thisClass ." extends DataObjectDao __construct" . \PHP_EOL ;
        // echo self::$thisClass . \PHP_EOL ;
        // print_r( $args ) ;

        $this->setValuesDefault ( $args ) ;
        parent::__construct( dbType: self::$dbType , thisClass: self::$thisClass , allowedKeys: self::$allowedKeys  ) ;
        $this->setupObject( self::$thisClass , $args ) ;
        $this->fixValuesType () ;

    }
}

?>
