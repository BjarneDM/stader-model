<?php   namespace stader\bootstrap ;

echo '<pre>' ;
echo \PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL . '-> entering : ' . basename( __file__ ) . \PHP_EOL ;

/*

create table if not exists flag
(
    id          int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;

 */

/*
 *  setup
 */

require_once( dirname( __file__ , 2 ) . '/model/class.classloader.php' ) ;
use \stader\model\{Flag,Flags} ;

/*
 *  data
 */

// https://www.php.net/manual/en/migration70.new-features.php#migration70.new-features.unicode-codepoint-escape-syntax
// https://www.utf8icons.com/
$symbols   = [] ;
// $symbols['] = "\u{}" ;
$symbols['ild'   ] =  "\u{1f525}" ; // https://www.utf8icons.com/character/128293/fire
$symbols['torden'] =  "\u{26a1}"  ; // https://www.utf8icons.com/character/9889/high-voltage-sign
$symbols['storm' ] =  "\u{1f32a}" ; // https://www.utf8icons.com/character/127786/cloud-with-tornado
$symbols['sne'   ] =  "\u{1f328}" ; // https://www.utf8icons.com/character/127784/cloud-with-snow
$symbols['regn'  ] =  "\u{2614}"  ; // https://www.utf8icons.com/character/9748/umbrella-with-rain-drops
$symbols['hede'  ] =  "\u{1f321}" ; // https://www.utf8icons.com/character/127777/thermometer
$symbols['fest'  ] =  "\u{1f389}" ; // https://www.utf8icons.com/character/127881/party-popper
$symbols['pizza' ] =  "\u{1f355}" ; // https://www.utf8icons.com/character/127829/slice-of-pizza
$symbols['kaffe' ] =  "\u{2615}"  ; // https://www.utf8icons.com/character/9749/hot-beverage
$symbols['politi'] =  "\u{1f46e}" ; // https://www.utf8icons.com/character/128110/police-officer
$symbols['brand' ] =  "\u{1f692}" ; // https://www.utf8icons.com/character/128658/fire-engine
$symbols['112'   ] =  "\u{1f691}" ; // https://www.utf8icons.com/character/128657/ambulance
$symbols['pil'   ] =  "\u{2933}"  ; // https://www.utf8icons.com/character/10547/wave-arrow-pointing-directly-right
$symbols['øl'    ] =  "\u{1f37a}" ; // https://www.utf8icons.com/character/127866/beer-mug
// $symbols[] = "\u{}" ;

/*
 *  main
 */

( new Flags() )->deleteAll() ;

foreach ( $symbols as $txt => $symbol )
{
    $thisFlag = new Flag( [ 'text' => $txt , 'unicode' => $symbol ] ) ;
}   unset( $txt , $symbol ) ;

foreach ( ( new Flags() ) as $flag )
    echo json_encode( $flag->getData() , JSON_UNESCAPED_UNICODE ) . \PHP_EOL ;
    unset( $flag ) ;

echo '</pre>' ;
?>
