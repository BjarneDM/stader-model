<?php   namespace stader\eksempler ;

/*
 *  setup
 */

set_include_path( '/Volumes/Bjarne/Sites/info/mathiesen/zbc/stader/php' ) ;
require_once( 'settings/phpValues.php' ) ;

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{User,UserLogin} ;


$method    = ( $argv[1] ) ?: 'manual' ;
$login     = ( $argv[2] ) ?: 'krkr' ;
$password  = ( $argv[3] ) ?: 'password' ;

/*
 *  data
 */

$passwords   = [] ;
$passwords[] = 'password' ; 
$passwords[] = 'baqXw32u' ; $passwords[] = 'ayuTZlQc' ; $passwords[] = '4CMXxNpx' ; 
$passwords[] = 'pLTexy9O' ; $passwords[] = 'flnQpN6I' ; $passwords[] = 'I3vfxD8a' ; 
$passwords[] = 'ReoOJeVJ' ; $passwords[] = 'gbEwK77H' ; $passwords[] = 'i4fIeg6o' ; 
$passwords[] = 'WYa9yhZ8' ; $passwords[] = 'dqJzbf6D' ; $passwords[] = '56JCxPRK' ;

$userNames = 
[
    'bjar9215' , 'casp7654'  , 'krik.zbc' , 'mike4098'   , 'toke1254' , 
    'alex303a' , 'zbcadbac1' , 'ande319i' , 'zbcanols21' , 'benj6414' 
] ;

/*
 *  main
 */

switch ( $method )
{
    case 'manual' :
        echo "manuelt tjek af en bruger" . PHP_EOL ;
        print_r( [ 'username' => $login , 'passwd' => $password ] ) ;
        echo theCheck( $login , $password ) ;
        break ;
    case 'auto' :
        echo "automatiserede tjek af brugere" . PHP_EOL ;
        $users = setUpOK() ;
        foreach ( $users as $user )
        {
            print_r( [ 'username' => $user['username'] , 'passwd' => $user['passwd'] ] ) ;
            echo $user['result'] . ' : ' . theCheck( $user['username'] , $user['passwd'] ) . \PHP_EOL ;
        }
        $users = setUpPassword() ;
        foreach ( $users as $user )
        {
            print_r( [ 'username' => $user['username'] , 'passwd' => $user['passwd'] ] ) ;
            echo $user['result'] . ' : ' . theCheck( $user['username'] , $user['passwd'] ) . \PHP_EOL ;
        }
        $users = setUpUsername() ;
        foreach ( $users as $user )
        {
            print_r( [ 'username' => $user['username'] , 'passwd' => $user['passwd'] ] ) ;
            echo $user['result'] . ' : ' . theCheck( $user['username'] , $user['passwd'] ) . \PHP_EOL ;
        }
        break ;
}

function theCheck( $login , $password )
{

    $user = new UserLogin( [ 'username' => $login , 'passwd' => $password ] ) ;

    if ( is_null( $user->getData() ) ) 
        { return 'login fejlede' ; }
    else
        { return 'succefuld login' ;}
}

function theCheck2( $login , $password )
{

    $user = new UserLogin( [ 'username' => $login , 'passwd' => $password ] ) ;

    if ( is_null( $user->getData() ) ) 
        { return null ; }
    else
        { return $user ; }
}

function theCheck3( $login , $password )
{

    $loginCheck = true ;

    try 
    {
        $user = new User( 'username' , $login ) ;

        if ( ! $user->pwdVerify( $password ) )
        {
            $loginCheck = false ;
        }

    } catch ( \Exception $e ) {
        $loginCheck = false ;
    }

return $loginCheck ; }

function setUpOK ()
{   global $userNames , $passwords ;

    $i = 0 ;
    $users = [] ;

    foreach ( $userNames as $userName )
    {
        $users[] = 
        [ 
            'username' => $userName ,
            'passwd'   => "{$passwords[$i++]}" , 
            'result'   => 'OK'
        ] ;
    }

return $users ; }

function setUpPassword ()
{   global $userNames , $passwords ;

    $i = 2;
    $users = [] ;

    foreach ( $userNames as $userName )
    {
        $users[] = 
        [ 
            'username' => $userName ,
            'passwd'   => "{$passwords[$i++]}" , 
            'result'   => 'password er forkert'
        ] ;
    }

return $users ; }

function setUpUsername ()
{   global $userNames , $passwords ;

    $i = 0 ;
    $users = [] ;

    foreach ( $userNames as $userName )
    {
        $users[] = 
        [ 
            'username' => 'x' . $userName . 'x' ,
            'passwd'   => "{$passwords[$i++]}" , 
            'result'   => 'username er forkert'
        ] ;
    }

return $users ; }
?>
