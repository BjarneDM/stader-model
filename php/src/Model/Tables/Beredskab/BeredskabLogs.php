<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\DataObjectsDao ;
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

class BeredskabLogs extends DataObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\Beredskab\\BeredskabLog' ;

    use DataObjectsConstruct ;

}

?>
