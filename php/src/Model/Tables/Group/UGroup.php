<?php namespace Stader\Model\Tables\Group ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

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
    public static $dbType      = 'data' ;
    public static $thisClass  = '\\Stader\\Model\\Tables\\Group\\UGroup' ;
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'varchar'
        ] ;

    use DataObjectConstruct ;

}

?>
