<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\DateTimeString ;
use \Stader\Model\Traits\{DataObjectConstruct,LogNotify} ;

/*

create table if not exists place
(
    id              int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    header          text ,
    place_owner_id  int default null ,
        foreign key (place_owner_id) references placeowner(id)
        on update cascade 
        on delete restrict ,
    area_id         int ,
        foreign key (area_id) references area(id)
        on update cascade 
        on delete cascade ,
    lastchecked     datetime
        default   null ,
    active          bool default true ,

    unique key (place_nr,area_id)
) ;

 */

class Place extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Place\\Place' ;
    public static $allowedKeys = 
        [ 'place_nr'       => 'varchar' , 
          'description'    => 'text'    , 
          'place_owner_id' => 'int'     , 
          'area_id'        => 'int'     , 
          'active'         => 'bool'
        ] ;
    public static $privateKeys = [] ;
    private $thisLog ;
    private $referenceID ;
    private $descriptID  ;

    use DataObjectConstruct ;

    protected function setValuesDefault ( &$args ) : void
    {
        $this->thisLog = self::$thisClass . 'Log' ;
        $this->referenceID = array_keys( PlaceLog::$allowedKeys )[0] ;
        $this->descriptID  = array_keys( PlaceLog::$allowedKeys )[1] ;
    }

    protected function fixValuesType () : void {
        $this->values['place_owner_id'] 
        =   @is_null( $this->values['place_owner_id'] ) 
            ? null 
            : (int)  $this->values['place_owner_id'] ;
        $this->values['area_id']        = (int)  $this->values['area_id']        ;
        $this->values['active']         = (bool) $this->values['active']         ;
        $this->values['lastchecked']
        =   @is_null( $this->values['lastchecked'] ) 
            ? null 
            : DateTimeString::createFromFormat( 'Y-m-d H:i:s' , $this->values['lastchecked'] ) ;

    }

    public function setChecked( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        switch ( strtolower( gettype( $args[0] ) ) )
        {
            case 'null' :
                $this->setValues( [ 'lastchecked' => ( new \DateTime() )->format( 'Y-m-d H:i:s' ) ] ) ;
                break ;
            case 'datetime' :
                $this->setValues( [ 'lastchecked' => $args[0]->format( 'Y-m-d H:i:s' ) ] ) ;
                break ;
            default :
                throw new \Exception( gettype( $args[0] ) . " : forkert input type [null,datetime]" ) ;
                break ;
            
        }
    }

    use LogNotify ;

}

?>
