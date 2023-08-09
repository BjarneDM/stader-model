<?php namespace Stader\Model ;

// https://refactoring.guru/design-patterns/singleton/php/example
// https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php

use \Stader\Model\Traits\{MagicMethods,SingletonSetup} ;

class Settings
{
    use SingletonSetup ;
    use MagicMethods ;

    private function __construct()
    {
            $this->values = 
                parse_ini_file( 
                    dirname( __file__ , 3 ) . '/settings/connect.ini' , 
                    true 
                ) ;

    // print_r( $this->values) ;

    }

    public static function getInstance()
    {
        if( ! self::$instance )
        {
          self::$instance = new Settings() ;
        }
    return self::$instance ; }

    public function getSetting( ...$args ): string | int | bool
    {
        switch ( count( $args ) )
        {
            case 1 :
                return $this->values[$args[0]] ;
                break;
            case 2 :
                return $this->values[$args[0]][$args[1]] ;
               break;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1,2]" ) ;
                break ;
        }
    }

}

?>
