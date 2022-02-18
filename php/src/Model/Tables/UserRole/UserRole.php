<?php namespace Stader\Model\Tables\UserRole ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Control\User\{User} ;
use \Stader\Model\Tables\Role\{URole} ;
use \Stader\Model\Traits\DataObjectConstruct ;

/*

create table if not exists users_roles
(
    id          int auto_increment primary key ,
    user_id     int ,
        foreign key (user_id) references userscrypt(id)
        on update cascade 
        on delete cascade ,
    role_id     int ,
        foreign key (role_id) references roles(id)
        on update cascade 
        on delete cascade
) ;

 */

class UserRole extends DataObjectDao
{
    public static $dbType      = 'data' ;
    public static $allowedKeys = 
        [ 'user_id' => 'int' , 
          'role_id' => 'int' 
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\UserRole\\UserRole' ;

    use DataObjectConstruct ;

    protected function setValuesDefault ( &$args ) : void 
    {
        try {
            /*  [ 'user_id'  => 'int'    , 
                  'role_id'  => 'int'    ,
                  'username' => 'string' ,
                  'email'    => 'string' ,
                  'role'     => 'string'
                ] ; */
            $args[0] = $this->convertKeys( $args[0] ) ;
        } catch ( \TypeError $e ) {}
    }

    protected function fixValuesType () : void 
    {
        $this->values['user_id'] = (int) $this->values['user_id'] ;
        $this->values['role_id'] = (int) $this->values['role_id'] ;
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
            // konverterer [ 'role' ] -> role_id
            if ( in_array( $key , [ 'role' ] ) )
            {
                $role = new URole( $key , $value ) ;
                $ids['role_id'] = $role->getData()['id'] ;
            }
             // tjekker om der findes en role m/ [ 'role_id' ]
           if ( in_array( $key , [ 'role_id' ] ) )
            {
                $role = new URole( $value ) ;
                $ids['role_id'] = $role->getData()['id'] ;
            }
            unset( $user , $role) ;
        } unset( $key , $value ) ;

    return $ids ; }

}

?>
