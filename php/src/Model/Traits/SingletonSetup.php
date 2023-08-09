<?php namespace Stader\Model\Traits ;

trait SingletonSetup
{
    private static $instance = null ;
    private $values ;
    
    private function __clone() {}
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

/*
    public static function getInstance()
    {
        if( ! self::$instance )
        {
          self::$instance = new Settings() ;
        }
    return self::$instance ; }
*/

}
