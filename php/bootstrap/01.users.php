<?php   namespace Stader\Bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;


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

/*
 *  setup
 */

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Control\Objects\User\{User,Users} ;
use \Stader\Model\RandomStr ;

/*
 *  data
 */

$passwords   = [] ;

// $passwords[] = 'password' ; 
$passwords[] = 'baqXw32u' ; $passwords[] = 'ayuTZlQc' ; $passwords[] = '4CMXxNpx' ; 
$passwords[] = 'pLTexy9O' ; $passwords[] = 'flnQpN6I' ; $passwords[] = 'I3vfxD8a' ; 
$passwords[] = 'ReoOJeVJ' ; $passwords[] = 'gbEwK77H' ; $passwords[] = 'i4fIeg6o' ; 
$passwords[] = 'WYa9yhZ8' ; $passwords[] = 'dqJzbf6D' ; $passwords[] = '56JCxPRK' ;

// $passwords[] = 'z@!:;va}$k,D|#G=?*:pAqc|' ;
// $passwords[] = 'HTPX)_v~A\\iP1@J9k,.VDvYQ' ;
// $passwords[] = '7:3(odvGh)T--jcfZqtT|Kz*' ;
// $passwords[] = '+za\'s0M3zgd.a8Bq2pzd*}qw' ;
// $passwords[] = 'cS=QIW>-x3ObEQJ06$~J8Dy2' ;
// $passwords[] = 'X3*vR7,|:v.mQcQ{7fC1}dw9' ;
// $passwords[] = '2u\\N4X6z~ECq%C\'jIR@nd&@M' ;
// $passwords[] = 'Tt~f2?\'\'/&;jW%lgzsP$LFNK' ;
// $passwords[] = '5G)AG%SIJ209x*;H,Je{vaj%' ;
// $passwords[] = 'fD}\'k(>?2/ymJb/8_1J(>}!}' ;
// $passwords[] = 'T{6QTtw3|FC0]^kh\'zEf8^:B' ;
// $passwords[] = 'C+k%2G/dheWP9LCC9fvtro<T' ;
// $passwords[] = '%]W7xVH\'lgF1ur>NUk#ev)+m' ;
// $passwords[] = 'nVJQZ@V~@g!oOCu}a|oP2y=k' ;
// $passwords[] = '&-qj+VxGy29Ldl(S5sTz.wH?' ;
// $passwords[] = '&*#[[Fr;YXM*+dhY+Nn?wj!v' ;
// $passwords[] = '6\\u.RDpPyyBvnSTXOy}&i0M;' ;
// $passwords[] = 'FJ5d16ZGl(:J$LoiiP^\'b1nt' ;
// $passwords[] = '\\q\'E-R#-;\'#S.tO5<n9(b4@4' ;
// $passwords[] = ']+m:{%kVM/3:%F9IFZq.F$Ne' ;
// $passwords[] = 'HIrR0|qAC}a1)?Q>ysEtb$91' ;
// $passwords[] = 'yq*P+o7s2Kt<bmkYz\'\\Wj21|' ;
// $passwords[] = '@[8fz).80+QJv}Mgw\\PN!5NL' ;
// $passwords[] = 'o|<OA%=rA\'hm!&x[jcX}f8%m' ;

$password = new RandomStr( [ 'length' => 8 , 'ks' => 0 ] ) ;
$phone    = new RandomStr( [ 'length' => 4 , 'ks' => 4 ] ) ;
// print_r( $phone) ; exit() ;

$i = 0 ;
//     [ 'name' => '' , 'surname' => '' , 'phone' => '' , 'username' => '' , 'passwd' => $passwords[$i++ % count( $passwords )] , 'email' => '' ] ,
$users =
[
//     [ 'name' => 'dummy' , 'surname' => '' , 'phone' => '' , 'username' => 'dummy' , 'passwd' => '' , 'email' => '' ] ,

    [ 'name' => 'Michael' , 'surname' => '' , 'phone' => 'D' , 'username' => 'mich' , 'passwd' => $passwords[$i++ % count( $passwords )] , 'email' => '' ] ,
//     [ 'name' => 'Lars' , 'surname' => 'Starbech' , 'phone' => 'B' , 'username' => 'last' , 'passwd' => $passwords[$i++ % count( $passwords )] , 'email' => '' ] ,
//     [ 'name' => 'SKP-IT' , 'surname' => 'Slagelse' , 'phone' => 'A' , 'username' => 'skp' , 'passwd' => $passwords[$i++ % count( $passwords )] , 'email' => '' ] ,
//     [ 'name' => 'Lars' , 'surname' => 'Nielsen' , 'phone' => 'S' , 'username' => 'lani' , 'passwd' => $passwords[$i++ % count( $passwords )] , 'email' => '' ] ,

    [ 'name' => 'Kris' , 'surname' => 'Kristensen' , 'phone' => 'C' , 'username' => 'krkr' , 'passwd' => $passwords[$i++ % count( $passwords )] , 'email' => '2' ] 

] ;

foreach ( $users as $key => $user )
{
    $users[ $key ]['email'] = $user['username'] . '@example.com' ;
}   unset( $key , $user ) ; 

/*
 *  main
 */

( new Users() )->deleteAll() ; // echo __LINE__ ; exit ;

/*
$testUser = new User( $users[0] ) ;
print_r( $testUser->getData() ) ;
$testUser->delete() ;
print_r( $testUser->getData() ) ;
exit ;
 */

foreach ( $users as $key => $user )
{
    $user['phone'] = $phone->next() . ' ' . $phone->next() ;
    $thisUser = new User( $user ) ;
    print_r( $thisUser->getData() ) ;
}   unset( $key , $user ) ;

foreach ( ( new Users() ) as $user )
    echo json_encode( $user->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $user ) ;

echo '</pre>' ;
?>

