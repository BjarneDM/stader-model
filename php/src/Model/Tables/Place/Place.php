<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\ObjectDao ;
use \Stader\Model\OurDateTime ;

/*

create table if not exists place
(
    id              int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
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

class Place extends ObjectDao
{
    public static $allowedKeys = 
        [ 'place_nr'       => 'varchar' , 
          'description'    => 'text'    , 
          'place_owner_id' => 'int'     , 
          'area_id'        => 'int'     , 
          'active'         => 'bool'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\Place\\Place' ;

    function __construct ( ...$args )
    {   // echo 'class Place extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['place_owner_id'] = @is_null( $this->values['place_owner_id'] ) 
                                          ? null 
                                          : (int)  $this->values['place_owner_id'] ;
        $this->values['area_id']        = (int)  $this->values['area_id']        ;
        $this->values['active']         = (bool) $this->values['active']         ;
        $this->values['lastchecked']    = @is_null( $this->values['lastchecked'] ) 
                                          ? null 
                                          : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['lastchecked'] ) ;

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
}

?>
