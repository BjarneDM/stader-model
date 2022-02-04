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

    private $kSpace = [] ;
    private $keyspaces = [] ;

    function initKeySpaces ()
    {
        // $kSpace[''] = '' ;
        $this->kSpace['digits']  = '0123456789' ;
        $this->kSpace['enLower'] = 'abcdefghijklmnopqrstuvwxyz' ;
        $this->kSpace['enUpper'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ;
        $this->kSpace['special'] = '-=~!@#$%^&*()_+,./<>?;:[]{}\\|\'' ;
        $this->kSpace['daDK']    = 'æøåÆØÅ' ;

        
        // array[string] $keyspaces    Strings of all possible characters to select from
        $this->keyspaces[0] =  $this->kSpace['digits'] . $this->kSpace['enLower'] . $this->kSpace['enUpper'] ;
        $this->keyspaces[1] =  $this->keyspaces[0] . $this->kSpace['special'] ;
        $this->keyspaces[2] =  $this->keyspaces[0] . $this->kSpace['daDK'] ;
        $this->keyspaces[3] =  $this->keyspaces[0] . $this->kSpace['special'] . $this->kSpace['daDK'] ;
        $this->keyspaces[4] =  $this->kSpace['digits'] ;
        $this->keyspaces[5] =  $this->kSpace['digits'] . 'abcdef' ;
        $this->keyspaces[6] =  $this->kSpace['digits'] . 'ABCDEF' ;
    }

    public function getKeyspaces ()
    {
        $this->initKeySpaces() ;
        print_r( $this->keyspaces ) ;
    }

    function __construct( ...$args )
    {   // echo 'class RandomStr __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        $this->initKeySpaces() ;

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

        

        /*
         *  tjek
         *  let's make sure we always return something sensible
         */
        $this->ks = max( 0 , min( count( $this->keyspaces ) -1 , (int) $this->ks ) ) ; // 0 <= ks < count(keyspaces)
        if   ( $this->ks === 4 )
             { $this->length = max( 4 , ((int) $this->length) %  7 ) ; } // PinKoder
        else { $this->length = max( 8 , ((int) $this->length) % 33 ) ; } // 8 <= length <= 32

        $this->keyspace = $this->keyspaces[ $this->ks ] ;
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
