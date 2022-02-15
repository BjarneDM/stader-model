<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\OurDateTime ;

/*

create table if not exists userlogin
(
    id              int auto_increment primary key ,
    username        varchar(255) not null ,
        constraint  unique (username) ,
    email           varchar(255) not null ,
        constraint  unique (email) ,
    passwd          varchar(255) not null ,
    lastlogintime   datetime
        default     null ,
    lastloginfail   datetime
        default     null ,
    loginfailures   int default 0 
) ;

 */

class UserLogin extends DataObjectDao
{
    public  static $allowedKeys = 
        [ 'username'      => 'varchar' , 
          'passwd'        => 'varchar' , 
          'email'         => 'varchar'
        ] ;
    private static $privateKeys =
        [ 'lastlogintime' => 'datetime' ,
          'lastloginfail' => 'datetime' ,
          'loginfailures' => 'int'
        ] ;
    protected   $class  = '\\Stader\\Model\\Tables\\User\\UserLogin' ;

    function __construct ( ...$args )
    {   // echo 'class UserLogin extends DataObjectDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct( 'data' , self::$allowedKeys ) ;

        $this->setupData( $args ) ;
        $this->values['id']             = (int) $this->values['id'] ;
        $this->values['lastlogintime']  = @is_null( $this->values['lastlogintime']   ) 
                                          ? null
                                          : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['lastlogintime']   ) ;
        $this->values['lastloginfail']  = @is_null( $this->values['lastloginfail'] ) 
                                          ? null 
                                          : OurDateTime::createFromFormat( 'Y-m-d H:i:s' , $this->values['lastloginfail'] ) ;
        $this->values['loginfailures']  = @empty( $this->values['loginfailures'] )
                                          ? 0
                                          : (int) $this->values['loginfailures'] ;


    }

    public function setLoginTime() : void
    {
        $this->valuesOld['lastlogintime'] = $this->values['lastlogintime'] ;
        $this->values['lastlogintime']    = new OurDateTime() ;
        $this->valuesOld['loginfailures'] = $this->values['loginfailures'] ;
        $this->values['loginfailures']    = 0 ;
        $this->update( $this ) ;
    }

    public function getLoginTime() : OurDateTime
    {
        return $this->getData()['lastlogintime'] ;
    }

    public function setLoginFailure() : void 
    {
        $this->valuesOld['lastloginfail'] = $this->values['lastloginfail'] ;
        $this->values['lastloginfail']    = new OurDateTime() ;
        $this->valuesOld['loginfailures'] = $this->values['loginfailures'] ;
        $this->values['loginfailures']++ ;
        $this->update( $this ) ;
    }

    public function getLoginFailure() : Array { return [] ; }

    private function pwdHash( string $password ) : string 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $password ] ) ;

        $options = [] ;
        $options['bcrypt'] = [ 'cost' => self::$iniSettings['crypt']['cost'] ] ;
        return 
            password_hash( 
                $password , 
                self::$iniSettings['crypt']['algoConst'] ,  
                $options['bcrypt'] 
            ) ;
    }

    protected function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        parent::check( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            switch ( $key )
            {
                case 'passwd' :
                    $options = [] ;
                    // un-hashed password
                    if ( in_array( password_get_info( $toCheck['passwd'] )['algo'] , [ 0 , null ] , true ) )
                    {
                        // If so, create a new hash, and replace the plain one
                        $toCheck[$key] = $this->pwdHash( $toCheck[$key] ) ;
                    }
                    break ;
            }
        }
    }

    public function pwdVerify( string $pwd ) : bool
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $pwd ] ) ;

        $result = password_verify( $pwd , $this->values['passwd'] ) ;
        if ( $result === true )
        {
            $pwdInfo = password_get_info( $this->values['passwd'] ) ;
            if (
                    $pwdInfo['algoName'] !== self::$iniSettings['crypt']['algoName']
                ||  (int) $pwdInfo['options']['cost'] !== (int) self::$iniSettings['crypt']['cost']
            )   $this->setValues( [ 'passwd' => $pwd ] ) ;
        }
    return $result ; }

}

?>