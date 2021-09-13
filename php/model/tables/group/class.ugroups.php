<?php namespace stader\model ;

class UGroups extends ObjectsDao
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'varchar'
        ] ;
    protected     $class       = '\\stader\\model\\UGroup' ;

    function __construct ( ...$args )
    {   // echo 'class UGroups extends ObjectsDao __construct' . \PHP_EOL ;
        // var_dump( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
