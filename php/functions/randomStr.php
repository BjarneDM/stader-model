<?php
/*
 * idea from :
 * https://stackoverflow.com/questions/6101956/generating-a-random-password-in-php/31284266#31284266
 *
 * Modified by : Bjarne Mathiesen - bjar9215
 *
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param  int  $length  How many characters do we want?
 * @param  int  $ks      An index into the possible keyspaces
 *
 * @return string
 */

/*
 *  setup
 */

$kSpace = [] ;
// $kSpace[''] = '' ;
$kSpace['digits']  = '0123456789' ;
$kSpace['enLower'] = 'abcdefghijklmnopqrstuvwxyz' ;
$kSpace['enUpper'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ;
$kSpace['special'] = '-=~!@#$%^&*()_+,./<>?;:[]{}\\|\'' ;
$kSpace['daDK']    = 'æøåÆØÅ' ;

// array[string] $keyspaces    Strings of all possible characters to select from
$keyspaces = [] ;
$keyspaces[0] =  $kSpace['digits'] . $kSpace['enLower'] . $kSpace['enUpper'] ;
$keyspaces[1] =  $keyspaces[0] . $kSpace['special'] ;
$keyspaces[2] =  $keyspaces[0] . $kSpace['daDK'] ;
$keyspaces[3] =  $keyspaces[0] . $kSpace['daDK'] . $kSpace['special'] ;
$keyspaces[4] =  $kSpace['digits'] ;

/*
 *  main()
 */

function randomStr(
    $length = 24 ,
    $ks     = 1
) { 
/*
 *  setup
 */
    global $keyspaces ;

/*
 *  tjek
 *  let's make sure we always return something sensible
 */
    $ks     = max( 0 , min( count( $keyspaces ) -1 , (int) $ks ) ) ; // 0 <= ks < count(keyspaces)
    if   ( $ks === 4 )
         { $length = max( 4 , ((int) $length) %  7 ) ; } // PinKoder
    else { $length = max( 8 , ((int) $length) % 33 ) ; } // 8 <= length <= 32

/*
 *  main()
 */
    $keyspace = $keyspaces[ $ks ] ;
    $str = '';
    $max = mb_strlen( $keyspace , '8bit' ) - 1 ;
    if ( $max < 1 ) { throw new Exception('$keyspace must be at least two characters long') ; }

    for ( $i = 0 ; $i < $length ; ++$i ) 
    {
        $str .= $keyspace[ random_int( 0 , $max ) ] ;
    }

return $str ; }

?>
