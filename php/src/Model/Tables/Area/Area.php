<?php namespace Stader\Model\Tables\Area ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

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
    public static $dbType      = 'data' ;
    public static $allowedKeys = 
        [ 'name'        => 'varchar' , 
          'description' => 'text' 
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Area\\Area' ;

    use DataObjectConstruct ;

}

?>
