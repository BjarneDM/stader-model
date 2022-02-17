<?php namespace Stader\Model\Tables\Ticket ;

use \Stader\Model\Abstract\DataObjectsDao ;
use \Stader\Model\Traits\DataObjectsConstruct ;

class Tickets extends DataObjectsDao
{
    private static $baseClass = '\\Stader\\Model\\Tables\\Ticket\\Ticket' ;

    use DataObjectsConstruct ;

}

?>
