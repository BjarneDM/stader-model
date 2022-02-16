<?php namespace Stader\Model\Tables\Place ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\OurDateTime ;
use \Stader\Model\Traits\DataObjectConstruct ;

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
    public static $allowedKeys = 
        [ 'place_nr'       => 'varchar' , 
          'description'    => 'text'    , 
          'place_owner_id' => 'int'     , 
          'area_id'        => 'int'     , 
          'active'         => 'bool'
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Place\\Place' ;

    use DataObjectConstruct ;

    function fixValuesType () : void {
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

    protected function notify ( string $action ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $action . PHP_EOL ;
        // print_r( $this->valuesOld ) ;
        // print_r( $this->values ) ;

        switch( $action )
        {
            case 'create' :
                new PlaceLog( [
                    'place_id'     => $this->values['id'] ,
                    'description'  => $this->values['description'] ,
                    'old_value'    => '' ,
                    'new_value'    => json_encode( $this->values )
                    ] ) ;
                break ;
            case 'read' :
                break ;
            case 'update' :
                new PlaceLog( [
                    'place_id'     => $this->values['id'] ,
                    'description'  => $this->values['description'] ,
                    'old_value'    => json_encode( array_diff( $this->valuesOld , $this->values ) ) ,
                    'new_value'    => json_encode( array_diff( $this->values , $this->valuesOld ) )
                    ] ) ;
                break ;
            case 'delete' :
                new PlaceLog( [
                    'place_id'     => $this->values['id'] ,
                    'description'  => $this->values['description'] ,
                    'old_value'    => json_encode( $this->values ) ,
                    'new_value'    => ''
                    ] ) ;
                break ;
        }

    }



}

?>
