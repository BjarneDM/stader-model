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
    public static $privateKeys = [] ;

    use DataObjectConstruct ;

    protected function fixValuesType () : void
    {
        $this->values['id']             = (int) $this->values['id'] ;
        $this->values['reference_id']   = (int) $this->values['reference_id'] ;
    }

}

?>
