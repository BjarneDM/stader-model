<?php namespace Stader\Model ;
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
 * 
 * @param  int  $length  How many characters do we want?
 * @param  int  $ks      An index into the possible keyspaces
 *
 * @return string
 */

class RandomStr
{
    private $length = 24 ;
    private $ks = 1 ;
    private $randomstr = '' ;
    private $keyspace = '' ;

    function __construct( ...$args )
    {   // echo 'class RandomStr __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        if ( count( $args ) == 1 )
        {
            foreach ( $args[0] as $key => $value ) 
            {
                if ( in_array( $key , [ 'length' , 'ks' ] ) )
                {
                    $this->$key = $value ;
                }
            }
        }
        // print_r( [ $this->length , $this->ks ] ) ;

        $kSpace = [] ;
        // $kSpace[''] = '' ;
        $kSpace['digits']  = '0123456789' ;
        $kSpace['enLower'] = 'abcdefghijklmnopqrstuvwxyz' ;
        $kSpace['enUpper'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ;
        $kSpace['special'] = '-=~!@#$%^&*()_+,./<>?;:[]{}\\|\'' ;
//         $kSpace['daDK']    = 'æøåÆØÅ' ;

        $keyspaces = [] ;
        // array[string] $keyspaces    Strings of all possible characters to select from
        $keyspaces[0] =  $kSpace['digits'] . $kSpace['enLower'] . $kSpace['enUpper'] ;
        $keyspaces[1] =  $keyspaces[0] . $kSpace['special'] ;
        $keyspaces[2] =  $keyspaces[0] ; // . $kSpace['daDK'] ;
        $keyspaces[3] =  $keyspaces[0]  . $kSpace['special'] ; // . $kSpace['daDK'] ;
        $keyspaces[4] =  $kSpace['digits'] ;
        $keyspaces[5] =  $kSpace['digits'] . 'abcdef' ;
        $keyspaces[6] =  $kSpace['digits'] . 'ABCDEF' ;

        /*
         *  tjek
         *  let's make sure we always return something sensible
         */
        $this->ks = max( 0 , min( count( $keyspaces ) -1 , (int) $this->ks ) ) ; // 0 <= ks < count(keyspaces)
        if   ( $this->ks === 4 )
             { $this->length = max( 4 , ((int) $this->length) %  7 ) ; } // PinKoder
        else { $this->length = max( 8 , ((int) $this->length) % 33 ) ; } // 8 <= length <= 32

        $this->keyspace = $keyspaces[ $this->ks ] ;
        $this->generate() ;
    }

    private function generate ()
    {
        $str = '';
//         $max = mb_strlen( $this->keyspace , '8bit' ) - 1 ;
        $max = strlen( $this->keyspace ) - 1 ;
        if ( $max < 1 ) { throw new \Exception('$keyspace must be at least two characters long') ; }

        for ( $i = 0 ; $i < $this->length ; ++$i ) 
        {
            $str .= $this->keyspace[ random_int( 0 , $max ) ] ;
        }
    $this->randomstr = $str ; }

    public function current() { return $this->randomstr ; }

    public function next()
    {
        $this->generate() ;
    return $this->randomstr ; }

    function __destruct() {}
}

?>
