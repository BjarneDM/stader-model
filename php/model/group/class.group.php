<?php namespace stader\model ;

/*

create table user_groups
(
    group_id    int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

 */

require_once( __dir__ . '/class.groupdao.php' ) ;

class Group extends GroupDao
{
    public static $allowedKeys = [ 'name' , 'description' ] ;

    function __construct ( ...$args )
    {   // echo 'class Group extends GroupDao __construct' . \PHP_EOL ;
        // var_dump( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[1] ) === 'integer' 
         *      opret en Group på basis af et group_id
         *      $testGroup = new Group( group_id ) ;
         *  gettype( $args[1] ) === 'array'
         *      opret en Group på basis af værdierne i $args[1]
         *      $testGroup = new Group( $newGroup )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent en group på basis af værdierne i $args[0],$args[1]
         *      $testGroup = new Group( $keys , $values )
         */

        // var_dump( $args[1] ) ;
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'integer' :
                        $this->read( $args[0] ) ;
                        break ;
                    case 'array' :
                        /*
                         *  count( $args[0] ) === 2 : ny group, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 2 :
                                $this->check( $args[0] ) ;
                                $this->values['group_id'] = $this->create( $args[0] ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[2] ) . " : forkert antal parametre [2]" ) ;
                                break ;
                        }

                       foreach ( $args[0] as $key => $value ) 
                        { 
                            $this->values[$key] = $value ;
                        }   unset( $key , $value ) ;

                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,array]" ) ;
                        break ;
                }
                break ;
            case 2 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'string' :
                        if ( strtolower( gettype( $args[1] ) ) !== 'string' )
                            throw new \Exception( gettype( $args[0] ) . ' & ' . gettype( $args[1] ) . ' er ikke begge "string"' ) ;
                         if ( ! in_array( $args[0] , self::$allowedKeys ) )
                            throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
                        break ;
                    case 'array' :
                        if ( count( $args[0] ) !== count( $args[1] ) )
                            throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                        foreach ( $args[0] as $key )
                        {
                            if ( ! in_array( $key , self::$allowedKeys ) )
                                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
                        }
                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [string,array]" ) ;
                        break ;
                }
                $this->read( $args[0] , $args[1] ) ;
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1,2]" ) ;
                break ;
        }
    }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! in_array( $key , self::$allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
        }
    }

    public function setValues( $values )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $values ) ;

        switch ( strtolower( gettype( $values ) ) )
        {
            case 'array' :
                $this->check( $values ) ;
                foreach ( $values as $key => $value )
                {
                    $this->values[ $key ] = $value ;
                    $this->update( $key , $value ) ;
                }
                break ;
            default :
                throw new \Exception( gettype( $values ) . " : forkert input type [array]" ) ;
                break ;
        }
    }

}

?>
