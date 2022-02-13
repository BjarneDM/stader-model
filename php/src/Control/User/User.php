<?php namespace Stader\Control\User ;

use \Stader\Model\Tables\User\{UserLogin,UsersLogin,UserInfo} ;
use \Stader\Control\Abstract\DataObjectDao ;

/*

create table users
(
    id          int auto_increment primary key , <- denne bliver genereret af DB
    name        varchar(255) not null ,          <- de resterende felter er krævede
    surname     varchar(255) default '' ,
    phone       varchar(255) not null ,
    username    varchar(255) not null ,
        constraint unique (username) ,
    passwd      varchar(255) not null ,
    email       varchar(255) not null ,
        constraint unique (email) ,
) engine = memory ;

 */

class User extends DataObjectDao
{
    public static $allowedKeys = 
        [ 'name'     => 'varchar' , 
          'surname'  => 'varchar' , 
          'phone'    => 'varchar' , 
          'username' => 'varchar' , 
          'passwd'   => 'varchar' , 
          'email'    => 'varchar' 
        ] ;
    protected   $class  = '\\Stader\\Control\\User\\User' ;

    protected UserInfo  $userInfo  ;
    protected UserLogin $userLogin ;

    function __construct ( ...$args )
    {   // echo 'class User extends ObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;
        
        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->usersLogin = new UsersLogin() ;

    }

    protected function read( $object ) : Array { return [] ; }
    protected function update( $values ) : void
    {
        foreach ( $values as $key => $value )
        {
            if ( in_array( $key , $this->userInfo::$allowedKeys  ) ) 
                { $this->userInfo->setValues(  [ $key => $value ] ) ; }
            if ( in_array( $key , $this->userLogin::$allowedKeys ) ) 
                { $this->userLogin->setValues( [ $key => $value ] ) ; }
        }
    }

    protected function delete() : void
    {
        $this->userLogin->delete() ;
        unset( $this->userInfo , $this->userLogin ) ;
        parent::delete() ;
    }

    protected function create () : int
    {
        $this->userLogin
            = new UserLogin([ 
                'username'     => $this->values['username'] ,
                'passwd'       => $this->values['passwd'] ,
                'email'        => $this->values['email']
                ]) ;
        $this->userInfo 
            = new UserInfo( [
                'name'         => $this->values['name'] ,
                'surname'      => $this->values['surname'] ,
                'phone'        => $this->values['phone'] ,
                'userlogin_id' => $this->userLogin->getData()['id']
                ]) ;
        $this->values['passwd'] = $this->userLogin->getData()['passwd'] ;
    return $this->userLogin->getData()['id'] ; }

}

?>
