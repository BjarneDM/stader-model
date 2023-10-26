<?php namespace Stader\Model\Traits ;

// https://refactoring.guru/design-patterns/singleton/php/example
// https://phpenthusiast.com/blog/the-singleton-design-pattern-in-php

trait SingletonSetup
{
    private static $instance = null ;
    private $values ;
    
    private function __clone() {}
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): self
    {
        if( ! self::$instance )
        {
            self::$instance = new self() ;
        }
    return self::$instance ; }

}
