<?php namespace Stader\Model\Tables\PlaceOwner ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

/*

create table if not exists placeowner
(
    id              int auto_increment primary key ,
    name            varchar(255) not null ,
    surname         varchar(255) not null ,
    phone           varchar(255) not null ,
    email           varchar(255) not null ,
    organisation    varchar(255) not null
) ;

 */

class PlaceOwner extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\PlaceOwner\\PlaceOwner' ;
    public static $allowedKeys = 
        [ 'name'         => 'varchar' , 
          'surname'      => 'varchar' , 
          'phone'        => 'varchar' , 
          'email'        => 'varchar' , 
          'organisation' => 'varchar' 
        ] ;

    use DataObjectConstruct ;

}

?>
