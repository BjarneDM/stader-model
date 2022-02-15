<?php   namespace stader\eksempler ;

/*
 *  setup
 */

set_include_path( dirname( __DIR__ ) ) ;
require_once( 'settings/phpValues.php' ) ;

require_once( 'classloader.php' ) ;
use \Stader\Control\User\{User} ;


$method    = @( $argv[1] ) ?: 'manual' ;
$login     = @( $argv[2] ) ?: 'krkr' ;
$password  = @( $argv[3] ) ?: 'flnQpN6I' ;

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
    'bjar9215' ,
    'mich' , 'last' , 'skp' , 'lani' , 'krkr'
] ;

/*
 *  main
 */

switch ( $method )
{
    case 'manual' :
        echo "manuelt tjek af en bruger" . PHP_EOL ;
        print_r( [ 'username' => $login , 'password' => $password ] ) ;
        echo theCheck( $login , $password ) ;
        break ;
    case 'auto' :
        echo "automatiserede tjek af brugere" . PHP_EOL ;

        $users = setUpOK() ;
        foreach ( $users as $user )
        {
            print_r( [ 'username' => $user['username'] , 'password' => $user['passwd'] ] ) ;
            echo $user['result'] . ' : ' . theCheck( $user['username'] , $user['passwd'] ) . \PHP_EOL ;
        }
 
        $users = setUpPassword() ;
        foreach ( $users as $user )
        {
            print_r( [ 'username' => $user['username'] , 'password' => $user['passwd'] ] ) ;
            echo $user['result'] . ' : ' . theCheck( $user['username'] , $user['passwd'] ) . \PHP_EOL ;
        }

        $users = setUpUsername() ;
        foreach ( $users as $user )
        {
            print_r( [ 'username' => $user['username'] , 'password' => $user['passwd'] ] ) ;
            echo $user['result'] . ' : ' . theCheck( $user['username'] , $user['passwd'] ) . \PHP_EOL ;
        }

        break ;
}

function theCheck( $login , $password )
{

    $user = User::userCheck( [ 'username' => $login , 'password' => $password ] ) ;

    if ( is_null( $user ) ) { return 'login fejlede' ; }
    else
    {   print_r( $user->getData() ) ;
        return 'succefuld login' ; }
}

function theCheck2( $login , $password )
{

    $user = User::userCheck( [ 'username' => $login , 'passwd' => $password ] ) ;

    if ( is_null( $user ) ) 
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
