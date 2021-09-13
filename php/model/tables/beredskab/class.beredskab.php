<?php namespace stader\model ;

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

class Beredskab extends ObjectDao
{
    public static $allowedKeys = 
        [ 'message'       => 'text'    , 
          'header'        => 'text'    , 
          'created_by_id' => 'int'     , 
          'flag'          => 'varchar' , 
          'colour'        => 'varchar' , 
          'active'        => 'bool' 
        ] ;
    protected     $class       = '\\stader\\model\\Beredskab' ;

    function __construct ( ...$args )
    {   // echo 'class Beredskab extends BeredskabDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

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
        $this->values['creationtime']    = @is_null( $this->values['creationtime'] ) ? new \DateTime() : \DateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['creationtime'] ) ;

    }

    public function switchOff()
    {
        $this->setValues( [ 'active' => false ] ) ;
    }

}

?>
