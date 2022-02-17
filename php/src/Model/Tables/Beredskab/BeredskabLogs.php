<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\LogObjectsDao ;
use \Stader\Model\Traits\DataObjectsConstruct ;

/*

create table if not exists beredskab_log
(
    id              int auto_increment primary key ,
    header          varchar(255) ,
    beredskab_id       int ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */

class BeredskabLogs extends LogObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\Beredskab\\BeredskabLog' ;

    use DataObjectsConstruct ;

}

?>
