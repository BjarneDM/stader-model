<?php namespace stader\model ;

/*

create table if not exists user_beredskab
(
    id              int auto_increment primary key ,
    user_id         int ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete cascade ,
    beredskab_id    int ,
        foreign key (group_id) references beredskab(beredskab_id)
        on update cascade 
        on delete cascade
) ;

 */

class UserBeredskab extends ObjectDao
{
    public static $allowedKeys = 
        [ 'user_id'      => 'int' ,
          'beredskab_id' => 'int'  
        ] ;
    protected     $class       = '\\stader\\model\\UserBeredskab' ;

    function __construct ( ...$args )
    {   // echo 'class UserBeredskab extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['user_id']      = (int) $this->values['user_id']      ;
        $this->values['beredskab_id'] = (int) $this->values['beredskab_id'] ;

    }

}

?>
