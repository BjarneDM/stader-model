<?php namespace Stader\Control\Objects\User ;

use \Stader\Model\Tables\User\{UserLogin,UsersLogin,UserInfo,LoginLog} ;
use \Stader\Model\OurDateTime ;
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
    public static $thisClass   = '\\Stader\\Control\\Objects\\User\\User' ;
    public static $allowedKeys = 
        [ 'name'     => 'varchar' , 
          'surname'  => 'varchar' , 
          'phone'    => 'varchar' , 
          'username' => 'varchar' , 
          'passwd'   => 'varchar' , 
          'email'    => 'varchar' 
        ] ;

    protected UserInfo  $userInfo  ;
    protected UserLogin $userLogin ;

    function __construct ( ...$args )
    {   // echo 'class User extends DataObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;
        
        parent::__construct( self::$allowedKeys ) ;

        $this->setupData( self::$thisClass , $args ) ;

    }

/*

create table if not exists userlogin
(
    id              int auto_increment primary key ,
    username        varchar(255) not null ,
    email           varchar(255) not null ,
    passwd          varchar(255) not null ,
    ip_addr         varchar(255) ,
    lastlogintime   datetime ,
    lastloginfail   datetime ,
    loginfailures   int default 0 
) ;

create table if not exists loginlog
(
    id              int auto_increment primary key ,
    user_id         int default null ,
    action          varchar(255) not null ,
    username        varchar(255) default null ,
    email           varchar(255) default null ,
    passwd          varchar(255) default null ,
    ip_addr         varchar(255) ,
    lastlogintime   datetime default null ,
    lastloginfail   datetime default null ,
    loginfailures   int default 0 
) ;

 */

    public static function userCheck ( Array $args ) : User|null
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $loginCheck = 'success' ;
        $values     = []   ;
        $nullValues = []   ;
        foreach ( LoginLog::$allowedKeys as $key => $type )
            { $nullValues[ $key ] = null ; }
            unset( $key , $type ) ;
        $returnValue = null ;

        try {
            $user = new UserLogin( array_keys( $args) , array_values( $args ) ) ;

            if ( ! $user->pwdVerify( $args['password'] ) )
            {
                $loginCheck = 'password' ;
                // echo "password tjek fejler" . \PHP_EOL ;
            }

        } catch ( \Exception $e ) {
            // echo "username tjek fejler" . \PHP_EOL ;
            $loginCheck = 'username' ;
        }

        switch ( $loginCheck ) 
        {
            case 'success' :
                // echo 'du bliver nu logget ind ' . \PHP_EOL ;

                $user->setLoginTime() ;
                $values = $user->getData() ;
                $values['action']  = $loginCheck   ;
                $values['user_id'] = $values['id'] ;
                unset( $values['id'] , $values['email'] , $values['passwd'] ) ;

                $returnValue = new User( $user->getData()['id'] ) ;
                break ;
            case 'password' :
                // echo "password tjek fejler" . \PHP_EOL ;
                // echo 'fejl i login ' . \PHP_EOL ;

                $user->setLoginFailure() ;
                $values = ( new \ArrayObject( $user->getData() ) )->getArrayCopy() ;
                $values['action']  = $loginCheck   ;
                $values['user_id'] = $values['id'] ;
                $values['passwd']  = $args['password'] ;
                unset( $values['id'] , $values['email'] ) ;

                break ;
            case 'username' :
                // echo "username tjek fejler" . \PHP_EOL ;
                // echo 'fejl i login ' . \PHP_EOL ;

                $values = ( new \ArrayObject( $args ) )->getArrayCopy() ;
                $values['action']  = $loginCheck   ;
                $values['passwd']  = $args['password'] ;
                $values['ip_addr'] = isset( $_SERVER['REMOTE_ADDR'] ) ?: null ;
                $values['lastloginfail'] = new OurDateTime() ;
                unset( $values['password'] ) ;

                break ;
        }

        $values = array_merge( $nullValues , $values ) ;
        new LoginLog( $values ) ;

    return $returnValue ; }

    protected function read() : Array 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $values  = [] ;
        $ulKeyes = [] ; $ulValues = [] ;
        $uiKeyes = [] ; $uiValues = [] ;

        if ( isset( $this->values['id'] ) )
        {
            $this->userLogin = new UserLogin( $this->values['id'] ) ;
            $this->userInfo  = new UserInfo( 'reference_id' , (int) $this->userLogin->getData()['id'] ) ;

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
                $this->userInfo  = new UserInfo( ['reference_id'] , [  $this->userLogin->getData()['id'] ] ) ;
            }
            /*
            if ( ! empty( $uiKeyes ) )
            {
                $this->userInfo  = new UserInfo(  $uiKeyes , $uiValues ) ;
                $this->userLogin = new UserLogin( $this->userInfo->getData()['reference_id'] ) ;
            }
            */

        }

        $values = array_merge( $this->userInfo->getData() , $this->userLogin->getData() ) ;
        unset( $values['reference_id'] ) ;
        return $values ;
   }

    protected function check( $thisClass , Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! array_key_exists( $key , self::$allowedKeys ) )
                unset( $toCheck[ $key ] ) ;
        }

        parent::check( $thisClass , $toCheck ) ;
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

    public function setLoginTime() : void
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->userLogin->setLoginTime() ;
    }

    public function setLoginFailure() : void 
    {
        $this->userLogin->setLoginFailure() ;
    }

    public function delete() : void
    {
        $this->userLogin->delete() ;
        unset( $this->userInfo , $this->userLogin ) ;
        parent::delete() ;
    }

    // lavet for at teste userinfocrypt separat
    protected function createTest () : int
    {
        $this->userInfo 
            = new UserInfo( [
                'name'         => $this->values['name'] ,
                'surname'      => $this->values['surname'] ,
                'phone'        => $this->values['phone'] ,
                'reference_id' => 0
                ]) ;
    return $this->userInfo->getData()['id'] ; }

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
                'reference_id' => $this->userLogin->getData()['id']
                ]) ;
        $this->values['passwd'] = $this->userLogin->getData()['passwd'] ;
    return $this->userLogin->getData()['id'] ; }
}

?>
