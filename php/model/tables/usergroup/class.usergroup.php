<?php namespace stader\model ;

/*

create table usersgroups
(
    id          int auto_increment primary key ,
    user_id     int ,
        foreign key (user_id) references users(id)
        on update cascade 
        on delete cascade ,
    group_id    int ,
        foreign key (group_id) references user_groups(id)
        on update cascade 
        on delete cascade
) ;

 */

class UserGroup extends ObjectDao
{
    public static $allowedKeys = 
        [ 'user_id'  => 'int' , 
          'group_id' => 'int'
        ] ;
    protected     $class       = '\\stader\\model\\UserGroup' ;

    function __construct ( ...$args )
    {   // echo 'class UserGroup extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        try {
            /*  [ 'user_id'  => 'int' , 
                  'group_id' => 'int' ,
                  'username' => 'string' ,
                  'email'    => 'string' ,
                  'name'     => 'string' 
                ] ; */
            $args[0] = $this->convertKeys( $args[0] ) ;
        } catch ( \TypeError $e ) {}

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['user_id']  = (int) $this->values['user_id']  ;
        $this->values['group_id'] = (int) $this->values['group_id'] ;

    }

    private function convertKeys( Array $array )
    {
        $ids = [] ;
        foreach ( $array as $key => $value )
        {
            // konverterer [ 'username' , 'email' ] -> user_id
            if ( in_array( $key , [ 'username' , 'email' ] ) )
            {
                $user  = new  User( $key , $value ) ;
                $ids['user_id'] = $user->getData()['id'] ;
            }
            // tjekker om der findes en user m/ [ 'user_id' ]
            if ( in_array( $key , [ 'user_id' ] ) )
            {
                $user  = new  User( $value ) ;
                $ids['user_id'] = $user->getData()['id'] ;
            }
            // konverterer [ 'name' ] -> group_id
            if ( in_array( $key , [ 'name' ] ) )
            {
                $group = new UGroup( $key , $value ) ;
                $ids['group_id'] = $group->getData()['id'] ;
            }
            // tjekker om der findes en group m/ [ 'group_id' ]
            if ( in_array( $key , [ 'group_id' ] ) )
            {
                $group = new UGroup( $value ) ;
                $ids['group_id'] = $group->getData()['id'] ;
            }
            unset( $user , $group) ;
        } unset( $key , $value ) ;
 
    return $ids ; }

}

?>
