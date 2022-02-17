<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\LogObjectsDao ;
use \Stader\Model\Traits\DataObjectsConstruct ;

/*

create table if not exists place_log
(
    id              int auto_increment primary key ,
    header          varchar(255) ,
    place_id       int ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */

class PlaceLogs extends LogObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\Place\\PlaceLog' ;

    use DataObjectsConstruct ;

}

?>
