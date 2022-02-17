<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\OurDateTime ;
use \Stader\Model\Tables\Beredskab\BeredskabLog ;
use \Stader\Model\Traits\{DataObjectConstruct,LogNotify} ;

/*

create table if not exists beredskab
(
    id              int auto_increment primary key ,
    message         text not null ,
    header          text ,
    created_by_id   int not null ,
        foreign key (created_by_id) references usercrypt(id)
        on update cascade 
        on delete restrict ,
    active          boolean default true ,
    creationtime    datetime
        default current_timestamp ,
    colour          varchar(16) default 'red' ,
    flag            varchar(255) default null
) ;

 */

class Beredskab extends DataObjectDao
{
    public static $allowedKeys = 
        [ 'message'       => 'text'    , 
          'header'        => 'text'    , 
          'created_by_id' => 'int'     , 
          'flag'          => 'varchar' , 
          'colour'        => 'varchar' , 
          'active'        => 'bool' 
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Beredskab\\Beredskab' ;
    private $thisLog ;
    private $referenceID ;
    private $descriptID  ;

    use DataObjectConstruct ;

    protected function setValuesDefault ( &$args ) : void 
    {
        $this->thisLog = self::$thisClass . 'Log' ;
        $this->referenceID = array_keys( BeredskabLog::$allowedKeys )[0] ;
        $this->descriptID  = array_keys( BeredskabLog::$allowedKeys )[1] ;
 
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'array' :
                        $args[0]['active'] = $args[0]['active'] ?? true  ;
                        $args[0]['colour'] = $args[0]['colour'] ?? 'red' ;
                        $args[0]['flag']   = $args[0]['flag']   ?? null  ;
                        break ;
                }
                break ;
        }
    }

    protected function fixValuesType () : void 
    {
        $this->values['created_by_id'] = (int)  $this->values['created_by_id'] ;
        $this->values['active']        = (bool) $this->values['active']        ;
        $this->values['creationtime']  = 
            @is_null( $this->values['creationtime'] ) 
            ? new OurDateTime() : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['creationtime'] ) ;
    }

    public function switchOff()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->setValues( [ 'active' => false ] ) ;
    }

    use LogNotify ;

}

?>
