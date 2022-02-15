<?php namespace Stader\Model\Tables\Role ;

use \Stader\Model\Abstract\DataObjectDao ;

/*

create table if not exists urole
(
    id          int auto_increment primary key ,
    role        varchar(255) not null ,
        constraint unique (role) ,
    priority    int ,
        index (priority) ,
    note        text
) ;

 */

class URole extends DataObjectDao
{
    public static $allowedKeys = 
        [ 'role'     => 'varchar' , 
          'note'     => 'text'    , 
          'priority' => 'int'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Role\\URole' ;

    function __construct ( ...$args )
    {   // echo 'class URole extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['priority'] = (int) $this->values['priority'] ;

    }

}

?>
