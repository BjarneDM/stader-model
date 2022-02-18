<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\DataObjectsDao ;
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

class PlaceLogs extends DataObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\Place\\PlaceLog' ;

    use DataObjectsConstruct ;

}

?>
