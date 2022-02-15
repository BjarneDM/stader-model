<?php namespace Stader\Model\Tables\Group ;

use \Stader\Model\Abstract\DataObjectDao ;

/*

create table if not exists ugroup
(
    id          int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

 */

class UGroup extends DataObjectDao
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'varchar'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Group\\UGroup' ;

    function __construct ( ...$args )
    {   // echo 'class UGroup extends ObjectDao __construct' . \PHP_EOL ;
        // var_dump( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
