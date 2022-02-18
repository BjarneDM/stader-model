<?php namespace Stader\Model\Tables\TypeByte ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

/*

create table in not exists type_byte
(
    id      int auto_increment primary key ,
    name    varchar(255) ,
        constraint unique (name)
)

 */

class TypeByte extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $allowedKeys = [ 'name' => 'varchar' ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\TypeByte\\TypeByte' ;

    use DataObjectConstruct ;

}

?>
