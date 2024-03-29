<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\DateTimeString ;
use \Stader\Model\Traits\{DataObjectConstruct,LogFunctions} ;

/*

create table if not exists placelog
(
    id                  int auto_increment primary key ,
    place_id        int ,
        index (place_id) ,
    description         varchar(255) ,
        index (header) ,
    log_timestamp       datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value           text default null ,
    new_value           text default null
) ;

 */

class PlaceLog extends DataObjectDao
{
    public static $dbType      = 'logs' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Place\\PlaceLog' ;
    public static $allowedKeys = 
        [ 'place_id'     => 'int'     , 
          'description'  => 'varchar' , 
          'old_value'    => 'text'    , 
          'new_value'    => 'text'
        ] ;
    public static $privateKeys = [] ;
    private $referenceID ;
    private $descriptID  ;

    use DataObjectConstruct ;
    use LogFunctions ;

}

?>
