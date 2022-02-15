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
) ;

select * from userlogin as ul , userinfo as ui where ul.id = ui.userlogin_id \G
*************************** 3. row ***************************
           id: 99
     username: skp
        email: skp@example.com
       passwd: $2y$04$xDxkVtrjfaTO4QHBnEuqKe6rlK2LVFx0wpkQzDocNsIDfR3zvu1q2
lastlogintime: 2022-02-15 16:28:18
lastloginfail: 2022-02-15 16:20:13
loginfailures: 0
           id: 99
 userlogin_id: 99
         name: SKP-IT
      surname: Slagelse
        phone: 8892 4596

Array
(
    [id] => 99
    [name] => SKP-IT
    [surname] => Slagelse
    [phone] => 8892 4596
    [username] => skp
    [email] => skp@example.com
    [passwd] => $2y$04$xDxkVtrjfaTO4QHBnEuqKe6rlK2LVFx0wpkQzDocNsIDfR3zvu1q2
    [lastlogintime] => Stader\Model\OurDateTime Object
        (
            [displayFormat:Stader\Model\OurDateTime:private] => mysql
            [date] => 2022-02-15 16:28:18.000000
            [timezone_type] => 3
            [timezone] => Europe/Copenhagen
        )

    [lastloginfail] => Stader\Model\OurDateTime Object
        (
            [displayFormat:Stader\Model\OurDateTime:private] => mysql
            [date] => 2022-02-15 16:20:13.000000
            [timezone_type] => 3
            [timezone] => Europe/Copenhagen
        )

    [loginfailures] => 0

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
    {   // echo 'class User extends DataObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;
        
        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( $args ) ;

    }

    public static function userCheck ( Array $args ) : User|null
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        try {
            $password = $args['password'] ;
            unset( $args['password'] ) ;
            // print_r( $args ) ;
            $checkUser = new UserLogin( array_keys( $args ) , array_values( $args ) ) ;
            // echo "checkUser : " ; print_r( $checkUser->getData() ) ;
            if ( $checkUser->pwdVerify( $password ) )
            {
                $checkUser->setLoginTime() ;
                return new User( $checkUser->getData()['id'] ) ;
            } else {
                $checkUser->setLoginFailure() ;
            }
        } catch ( \Exception $e ) { return null ; }

    return null ; }

    protected function read() : Array 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $values  = [] ;
        $ulKeyes = [] ; $ulValues = [] ;
        $uiKeyes = [] ; $uiValues = [] ;

        if ( isset( $this->values['id'] ) )
        {
            $this->userLogin = new UserLogin( $this->values['id'] ) ;
            $this->userInfo  = new UserInfo( 'userlogin_id' , (int) $this->userLogin->getData()['id'] ) ;

        } else {

            foreach ( $this->values as $key => $value )
            {   
                if ( array_key_exists( $key , UserInfo::$allowedKeys  ) ) 
                {
                    $uiKeyes[]  = $key   ;
                    $uiValues[] = $value ;
                }
                if ( array_key_exists( $key , UserLogin::$allowedKeys ) ) 
                {
                    $ulKeyes[]  = $key   ;
                    $ulValues[] = $value ;
                }
            }   unset( $key , $value ) ;

            if ( ! empty( $ulKeyes ) ) 
            {
                $this->userLogin = new UserLogin( $ulKeyes , $ulValues ) ;
                $this->userInfo  = new UserInfo( ['userlogin_id'] , [  $this->userLogin->getData()['id'] ] ) ;
            }
            if ( ! empty( $uiKeyes ) )
            {
                $this->userInfo  = new UserInfo(  $uiKeyes , $uiValues ) ;
                $this->userLogin = new UserLogin( $this->userInfo->getData()['userlogin_id'] ) ;
            }

        }

        $values = array_merge( $this->userInfo->getData() , $this->userLogin->getData() ) ;
        unset( $values['userlogin_id'] ) ;
        return $values ;
   }

    protected function update( Array $values ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $values ) ;
        foreach ( $values as $key => $value )
        {   // print_r( [  $key , $value ] ) ;
            // print_r( array_keys( UserInfo::$allowedKeys ) ) ;
            if ( array_key_exists( $key , UserInfo::$allowedKeys  ) )
                { $this->userInfo->setValues(  [ $key => $value ] ) ; }
            // print_r( array_keys( UserLogin::$allowedKeys ) ) ;
            if ( array_key_exists( $key , UserLogin::$allowedKeys ) )
                { $this->userLogin->setValues( [ $key => $value ] ) ; }
        }
    }
    
    public function pwdVerify( $password )
    {
        return $this->userLogin->pwdVerify( $password ) ;
    }

    public function delete() : void
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
        // $this->values['passwd'] = $this->userLogin->getData()['passwd'] ;
    return $this->userLogin->getData()['id'] ; }

}

?>
