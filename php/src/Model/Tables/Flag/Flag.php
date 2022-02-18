<?php namespace Stader\Model\Tables\Flag ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

/*

create table if not exists flag
(
    id          int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;

 */

class Flag extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $allowedKeys = 
        [ 'text'    => 'varchar' , 
          'unicode' => 'char'
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Flag\\Flag' ;

    use DataObjectConstruct ;

}

?>
