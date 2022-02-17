<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\LogObjectsDao ;
use \Stader\Model\Traits\DataObjectsConstruct ;

class TicketLogs extends LogObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\Ticket\\TicketLog' ;

    use DataObjectsConstruct ;

}

?>
