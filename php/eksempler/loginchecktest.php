<?php   namespace stader\eksempler ;

set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;
require_once( 'settings/phpValues.php' ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{UserTest} ;

$login     = ( $argv[1] ) ?: '' ;
$password  = ( $argv[2] ) ?: '' ;


$loginCheck = true ;

try {
    $user = new UserTest( $setup::$connect , 'username' , $login ) ;

    $checkMethod = 'function' ;
    switch ( $checkMethod )
    {
        case 'inline' :
            $pwd_peppered =  hash_hmac( 'sha256', $password , 'c1isvFdxMDdmjOlvxpecFw' ) ;
            $pwd_peppered =  $password ;
            if ( ! password_verify( $pwd_peppered , $user->getData()['passwd'] ) ) 
            {
                $options = [] ;
                $options['bcrypt'] = [ 'salt' => 'aaaaaaaaaaaaaaaaaaaaaa' , 'cost' => 10 ] ;
                $options['argon2i'] = [] ;
                print_r( 
                    [
                        mb_strlen( $user->getData()['passwd'] , 'utf8' ) ,
                        password_get_info( $user->getData()['passwd'] ) ,
                        $pwd_peppered ,
                        preg_split( '/(\$)/' , $user->getData()['passwd'] ) ,
                        preg_split( '/(\$)/' , password_hash( $pwd_peppered , PASSWORD_BCRYPT , $options['bcrypt'] ) )
                    ] ) ;
                echo "password tjek fejler" . \PHP_EOL ;
                $loginCheck = false ;
            }
            break ;
        case 'function' :
            if ( ! $user->pwdVerify( $password ) )
            {
                $loginCheck = false ;
                echo "password tjek fejler" . \PHP_EOL ;
            }
                break ;
    }
} catch ( \Exception $e ) {
    echo "username tjek fejler" . \PHP_EOL ;
    $loginCheck = false ;
}

switch ( $loginCheck ) 
{
    case true :
        echo 'du bliver nu logget ind ' . \PHP_EOL ;
        break ;
    case false :
        echo 'fejl i login ' . \PHP_EOL ;
        break ;
}

?>
