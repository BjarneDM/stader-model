<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\DataObjectsDao ;
use \Stader\Model\Traits\DataObjectsConstruct ;

class TicketLogs extends DataObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\Ticket\\TicketLog' ;

    use DataObjectsConstruct ;

}

?>
