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

use \Stader\Model\Traits\{MagicMethods} ;


class RandomStr
{
    private $randomstr = '' ;
    private $values = [] ;

    private static $kSpace = [] ;
    private static $keyspaces = [] ;


    private static function initKeySpaces ()
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        // $kSpace[''] = '' ;
        if ( count( self::$kSpace ) === 0 )
        {
            self::$kSpace['digits']  = '0123456789' ;
            self::$kSpace['enLower'] = 'abcdefghijklmnopqrstuvwxyz' ;
            self::$kSpace['enUpper'] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ;
            self::$kSpace['special'] = '-=~!@#$%^&*()_+,./<>?;:[]{}\\|\'' ;
            self::$kSpace['daDK']    = 'æøåÆØÅ' ;
        }

        // array[string] $keyspaces    Strings of all possible characters to select from
        if ( count( self::$keyspaces ) === 0 )
        {
            self::$keyspaces[0] =  self::$kSpace['digits'] . self::$kSpace['enLower'] . self::$kSpace['enUpper'] ;
            self::$keyspaces[1] =  self::$keyspaces[0] . self::$kSpace['special'] ;
            self::$keyspaces[2] =  self::$keyspaces[0] . self::$kSpace['daDK'] ;
            self::$keyspaces[3] =  self::$keyspaces[0] . self::$kSpace['special'] . self::$kSpace['daDK'] ;
            self::$keyspaces[4] =  self::$kSpace['digits'] ;
            self::$keyspaces[5] =  self::$kSpace['digits'] . 'abcdef' ;
            self::$keyspaces[6] =  self::$kSpace['digits'] . 'ABCDEF' ;
        }
    }

    public static function getKeyspaces ()
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        self::initKeySpaces() ;
        $outString = print_r( self::$keyspaces, true ) ;
        $outString = preg_replace( ['/^Array\n/','/Array/','/.*[(,)]\n/'], [''], $outString ) ;
        $outString = preg_replace( ['/\n\n/'], ["\n"], $outString ) ;
    return $outString ; }

    function __construct( int $length = 24 , int $ks = 1 )
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        $this->values['length'] = $length ;
        $this->values['ks'] = $ks ;

       self::initKeySpaces() ;       

        /*
         *  tjek
         *  let's make sure we always return something sensible
         */
        $this->values['ks'] = max( 0 , min( count( self::$keyspaces ) -1 , $this->values['ks'] ) ) ; // 0 <= ks < count(keyspaces)
        if   ( $this->values['ks'] === 4 )
             { $this->values['length'] = max( 4 , (min( 6, $this->values['length']))) ; } // PinKoder
        else { $this->values['length'] = max( 8 , (min(32, $this->values['length']))) ; } // 8 <= length <= 32

        $this->values['keyspace'] = self::$keyspaces[ $this->values['ks'] ] ;
        $this->generate() ;
    }

    private function generate ()
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        $str = '';
        $max = mb_strlen( $this->values['keyspace'] , '8bit' ) - 1 ;
//         $max = strlen( self::$keyspace ) - 1 ;
        if ( $max < 1 ) { throw new \Exception('$keyspace must be at least two characters long') ; }

        for ( $i = 0 ; $i < $this->values['length'] ; ++$i ) 
        {
            $str .= $this->values['keyspace'][ random_int( 0 , $max ) ] ;
        }
    $this->randomstr = $str ; }

    public function current()
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

    return $this->randomstr ; }

    public function next()
    {   //  echo __CLASS__ . " : " . __function__ . \PHP_EOL ;

        $this->generate() ;
    return $this->randomstr ; }

    use MagicMethods ;

    function __destruct() {}
}

?>
