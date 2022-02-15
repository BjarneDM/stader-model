<?php namespace Stader\Model\Tables\Area ;

use \Stader\Model\Abstract\DataObjectsDao ;

class Areas extends DataObjectsDao
{

    public function __construct ( ...$args )
    {   // echo 'class Area extends ObjectsDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        $this->keysAllowed = ( new \ArrayObject( Area::$allowedKeys ) )->getArrayCopy() ;
        $this->class = Area::$thisClass ;
        parent::__construct() ;
        $this->setupData( $args ) ;

    }

}

?>
