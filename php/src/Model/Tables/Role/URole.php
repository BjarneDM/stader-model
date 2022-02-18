<?php namespace Stader\Model\Tables\Role ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

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
    public static $dbType      = 'data' ;
    public static $allowedKeys = 
        [ 'role'     => 'varchar' , 
          'note'     => 'text'    , 
          'priority' => 'int'
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Role\\URole' ;

    use DataObjectConstruct ;

}

?>
