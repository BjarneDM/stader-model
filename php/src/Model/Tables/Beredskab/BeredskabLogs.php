<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\LogObjectsDao ;

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

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( BeredskabLog::$allowedKeys ) )->getArrayCopy() ;
        $this->class = BeredskabLog::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
