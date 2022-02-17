<?php   namespace Stader\Bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;



/*

create table users_groups
(
    users_groups_id     int auto_increment primary key ,
    user_id           int ,
        foreign key (user_id) references users(user_id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references user_groups(group_id)
        on update cascade 
        on delete cascade
) ;

$groups =
[
    [  'name' => 'BackOffice' , 'description' => 'Admin' ] ,
    [  'name' => 'Tlf.Sup.' , 'description' => 'Fjernsupport (modtager af Fejlmeldinger)' ] ,
    [  'name' => 'Klargøring' , 'description' => 'Pakke udstyr i basen' ] ,
    [  'name' => 'Transport' , 'description' => 'Sørger for at udstyr køres fra base til stadeplads' ] ,
    [  'name' => 'Runderings Tekniker' , 'description' => '' ] ,
    [  'name' => 'Support Tekniker' , 'description' => 'Løser fejl/opgaver (assistent til Rundereings Tekniker)' ] 
] ;

$users =
[
    [ 'name' => 'Bjarne' , 'surname' => 'Mathiesen' , 'phone' => '01234567' , 'username' => 'bjar9215' , 'passwd' => $passwords[0] , 'email' => '0' ] ,
    [ 'name' => 'Cassper' , 'surname' => 'Andersen' , 'phone' => '12345678' , 'username' => 'casp7654' , 'passwd' => $passwords[1] , 'email' => '1' ] ,
    [ 'name' => 'Kris' , 'surname' => 'Kristensen' , 'phone' => '23456789' , 'username' => 'krik.zbc' , 'passwd' => $passwords[2] , 'email' => '2' ] ,
    [ 'name' => 'Mike' , 'surname' => 'Kyed Jespersen' , 'phone' => '34567890' , 'username' => 'mike4098' , 'passwd' => $passwords[3] , 'email' => '3' ] ,
    [ 'name' => 'Toke' , 'surname' => 'Juholke Kejlow Nielsen' , 'phone' => '45678901' , 'username' => 'toke1254' , 'passwd' => $passwords[4] , 'email' => '4' ] ,
    [ 'name' => 'Alexander' , 'surname' => 'Lackovic Hansen' , 'phone' => '56789012' , 'username' => 'alex303a' , 'passwd' => $passwords[5] , 'email' => '5' ] ,
    [ 'name' => 'Adam' , 'surname' => 'Tayeb Bachir' , 'phone' => '67890123' , 'username' => 'zbcadbac1' , 'passwd' => $passwords[6] , 'email' => '6' ] ,
    [ 'name' => 'Anders' , 'surname' => 'Agerlund' , 'phone' => '78901234' , 'username' => 'ande319i' , 'passwd' => $passwords[7] , 'email' => '7' ] ,
    [ 'name' => 'Andi' , 'surname' => 'Olle Olsen' , 'phone' => '89012345' , 'username' => 'zbcanols21' , 'passwd' => $passwords[8] , 'email' => '8' ] ,
    [ 'name' => 'Benjamin' , 'surname' => 'Heltborg Roesdal' , 'phone' => '90123456' , 'username' => 'benj6414' , 'passwd' => $passwords[9] , 'email' => '9' ]
] ;

 */


/*
 *  setup
 */

require_once( dirname( __dir__ ) . '/classloader.php' ) ;
use \Stader\Model\Tables\Group\{UGroup,UGroups} ;
use \Stader\Control\User\{User,Users} ;
use \Stader\Model\Tables\UserGroup\{UserGroup,UsersGroups} ;

/*
 *  data
 */

$userNames = [] ;
$userNames['BackOffice']          = [ 'mich' ] ;
$userNames['Tlf.Sup.']            = [ 'last' , 'skp'  ] ;
$userNames['Klargøring']          = [ 'skp'  , 'lani' , 'krkr' ] ;
$userNames['Transport']           = [ 'lani' , 'krkr' , 'mich' , 'last' , 'skp'  ] ;
$userNames['Runderings Tekniker'] = [ 'krkr' , 'mich' , 'last' , 'skp'  , 'lani' ] ;
$userNames['Support Tekniker']    = [ 'mich' , 'last' , 'skp'  , 'lani' , 'krkr' ] ;
// $userNames['Mail']                = [ 'casp7654'  , 'LarsL' , 'kriskris' , 'MichaleM' , 'toke1254' , 'JanJ' , 'skp-IT' ] ;
// $userNames['Normal Besked']       = [ 'casp7654'  , 'LarsL' , 'kriskris' , 'MichaleM' , 'toke1254' , 'JanJ' , 'skp-IT' ] ;

/*
 *  main
 */

( new UsersGroups() )->deleteAll() ;

// print_r( $allGroups ) ;
foreach ( ( new UGroups() ) as $group )
{
    // print_r( $group ) ;
    /*
    foreach ( $userNames[ $group->getData()['name'] ] as $userName )
    {
        $thisUser = new User( 'username' , $userName ) ;
        $thisUserGroup = new UserGroup( [ 'user_id' => $thisUser->getData()['user_id'] , 'group_id' => $group->getData()['group_id'] ] ) ;
    } unset( $userName ) ;
    */

    echo $group->getData()['name'] . \PHP_EOL ;
    switch ( $group->getData()['name'] )
    {
        case 'BackOffice' :
            foreach ( $userNames[ $group->getData()['name'] ] as $userName )
            {
                $thisUser = new User( 'username' , $userName ) ;
                $thisUserGroup = new UserGroup( [ 'user_id' => $thisUser->getData()['id'] , 'group_id' => $group->getData()['id'] ] ) ;
            } unset( $userName ) ;
            break ;
         case 'Tlf.Sup.' :
            foreach ( $userNames[ $group->getData()['name'] ] as $userName )
            {
                $thisUser = new User( 'username' , $userName ) ;
                $thisUserGroup = new UserGroup( [ 'username' => $thisUser->getData()['username'] , 'group_id' => $group->getData()['id'] ] ) ;
            } unset( $userName ) ;
            break ;
         case 'Klargøring' :
            foreach ( $userNames[ $group->getData()['name'] ] as $userName )
            {
                $thisUser = new User( 'username' , $userName ) ;
                $thisUserGroup = new UserGroup( [ 'email' => $thisUser->getData()['email'] , 'group_id' => $group->getData()['id'] ] ) ;
            } unset( $userName ) ;
            break ;
        case 'Transport' :
            foreach ( $userNames[ $group->getData()['name'] ] as $userName )
            {
                $thisUser = new User( 'username' , $userName ) ;
                $thisUserGroup = new UserGroup( [ 'user_id' => $thisUser->getData()['id'] , 'name' => $group->getData()['name'] ] ) ;
            } unset( $userName ) ;
            break ;
         case 'Runderings Tekniker' :
            foreach ( $userNames[ $group->getData()['name'] ] as $userName )
            {
                $thisUser = new User( 'username' , $userName ) ;
                $thisUserGroup = new UserGroup( [ 'username' => $thisUser->getData()['username'] , 'name' => $group->getData()['name'] ] ) ;
            } unset( $userName ) ;
            break ;
         case 'Support Tekniker' :
            foreach ( $userNames[ $group->getData()['name'] ] as $userName )
            {
                $thisUser = new User( 'username' , $userName ) ;
                $thisUserGroup = new UserGroup( [ 'email' => $thisUser->getData()['email'] , 'name' => $group->getData()['name'] ] ) ;
            } unset( $userName ) ;
            break ;
    }
}

foreach( ( new UsersGroups() ) as $user_group )
{
    $group_user = $user_group->getData() ;
    $user = new User( (int) $user_group->getData()['user_id'] ) ;
    $group_user['user_id']  = implode( ' ' , [ $user->getData()['name'] , $user->getData()['surname'] ] ) ;
    $group_user['group_id'] = ( new UGroup( (int) $user_group->getData()['group_id'] ) )->getData()['name'] ;
    echo json_encode( $group_user , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
}   unset( $user_group ) ;


echo '</pre>' ;
?>
