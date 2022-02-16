<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\LogObjectsDao ;

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

    function __construct ( ...$args )
    {   // echo "class UGroups extends DataObjectsDao __construct" . \PHP_EOL ;
        // var_dump( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( PlaceLog::$allowedKeys ) )->getArrayCopy() ;
        $this->class = PlaceLog::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
