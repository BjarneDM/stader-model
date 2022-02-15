<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\OurDateTime ;
use \Stader\Model\Tables\Beredskab\BeredskabLog ;

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
    protected   $class  = '\\Stader\\Model\\Tables\\Beredskab\\Beredskab' ;

    function __construct ( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( self::$allowedKeys ) ;

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

        $this->setupData( $args ) ;
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

    protected function notify ( string $action ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $action . PHP_EOL ;
        // print_r( $this->valuesOld ) ;
        // print_r( $this->values ) ;

        switch( $action )
        {
            case 'create' :
                new BeredskabLog( [
                    'beredskab_id' => $this->values['id'] ,
                    'header'       => $this->values['header'] ,
                    'old_value'    => '' ,
                    'new_value'    => json_encode( $this->values )
                    ] ) ;
                break ;
            case 'read' :
                break ;
            case 'update' :
                new BeredskabLog( [
                    'beredskab_id' => $this->values['id'] ,
                    'header'       => $this->values['header'] ,
                    'old_value'    => json_encode( array_diff( $this->valuesOld , $this->values ) ) ,
                    'new_value'    => json_encode( array_diff( $this->values , $this->valuesOld ) )
                    ] ) ;
                break ;
            case 'delete' :
                new BeredskabLog( [
                    'beredskab_id' => $this->values['id'] ,
                    'header'       => $this->values['header'] ,
                    'old_value'    => json_encode( $this->values ) ,
                    'new_value'    => ''
                    ] ) ;
                break ;
        }

    }

}

?>
