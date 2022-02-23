<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\Traits\DataObjectConstruct ;

/*

create table if not exists userinfo
(
    id              int auto_increment primary key ,
    reference_id    int ,
    name            varchar(255) not null ,
    surname         varchar(255) not null ,
    phone           varchar(255) not null 
) ;

 */

class UserInfo extends DataObjectDao
{
    public static $dbType      = 'cryptdata' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\User\\UserInfo' ;
    public static $allowedKeys = 
        [ 'name'         => 'varchar' , 
          'surname'      => 'varchar' , 
          'phone'        => 'varchar' ,
          'reference_id' => 'int'
        ] ;

    // use DataObjectConstruct ;
    function __construct ( ...$args )
    {   // echo "class ". self::$thisClass ." extends DataObjectDao __construct" . \PHP_EOL ;
        // echo self::$thisClass . \PHP_EOL ;
        // print_r( $args ) ;

        $this->setValuesDefault ( $args ) ;
        // print_r( [ 'dbType' => self::$dbType , 'thisClass' => self::$thisClass , 'allowedKeys' => self::$allowedKeys ] ) ;
        parent::__construct( dbType: self::$dbType , thisClass: self::$thisClass , allowedKeys: self::$allowedKeys  ) ;
        $this->setupObject( self::$thisClass , $args ) ;
        $this->fixValuesType () ;

    }

    protected function fixValuesType () : void
    {
        $this->values['id']             = (int) $this->values['id'] ;
        $this->values['reference_id']   = (int) $this->values['reference_id'] ;
    }

}

?>
