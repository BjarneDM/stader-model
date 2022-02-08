<?php namespace Stader\Model\Tables\Area ;

use \Stader\Model\Abstract\DataObjectDao ;

/*

create table if not exists areas
(
    id          int auto_increment ,
        index(id) ,
    name        varchar(255) not null primary key ,
        constraint unique (name) ,
    description text
) ;

name er primary key da getAll() ønskes sorteret efter denne

 */

class Area extends DataObjectDao
{
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'text' 
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Area\\Area' ;

    public function __construct ( ...$args )
    {   // echo 'class Area extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

}

?>
