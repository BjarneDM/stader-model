<?php namespace Stader\Model\Tables\UserGroup ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Control\Objects\User\User ;
use \Stader\Model\Tables\Group\UGroup ;
use \Stader\Model\Traits\DataObjectConstruct ;

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

class UserGroup extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\UserGroup\\UserGroup' ;
    public static $allowedKeys = 
        [ 'user_id'  => 'int' , 
          'group_id' => 'int'
        ] ;
    public static $privateKeys = [] ;

    use DataObjectConstruct ;

    protected function setValuesDefault ( &$args ) : void 
    {
        try {
            /*  [ 'user_id'  => 'int' , 
                  'group_id' => 'int' ,
                  'username' => 'string' ,
                  'email'    => 'string' ,
                  'name'     => 'string' 
                ] ; */
            $args[0] = $this->convertKeys( $args[0] ) ;
        } catch ( \TypeError $e ) {}
        // print_r( $args ) ;
    }

    protected function fixValuesType () : void 
    {
        $this->values['user_id']  = (int) $this->values['user_id']  ;
        $this->values['group_id'] = (int) $this->values['group_id'] ;
    }

    private function convertKeys( Array $array )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $array ) ;
        $ids = [] ;
        foreach ( $array as $key => $value )
        {   // print_r( ['key'=>$key,'value'=>$value] ) ;
            // konverterer [ 'username' , 'email' ] -> user_id
            if ( in_array( $key , [ 'username' , 'email' ] ) )
            {
                $user  = new User( $key , $value ) ;
                $ids['user_id'] = $user->getData()['id'] ;
            }
            // tjekker om der findes en user m/ [ 'user_id' ]
            if ( in_array( $key , [ 'user_id' ] ) )
            {
                $user  = new User( $value ) ;
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

    // var_dump( $ids ) ; 
    return $ids ; }

}

?>
