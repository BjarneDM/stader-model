<?php namespace stader\model ;

/*

create table if not exists flags
(
    flag_id     int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;

 */

require_once( __dir__ . '/class.flagdao.php' ) ;

class Flag extends FlagDao
{
    public static $allowedKeys = [  'text' , 'unicode' ] ;

    function __construct ( ...$args )
    {   // echo 'class Flag extends FlagDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'integer' 
         *      hent en Flag på basis af et flag_id
         *      $testFlag = new Flag( flag_id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en flag på basis af værdierne i $args[0]
         *      $testFlag = new Flag( $newFlag )
         *  gettype( $args[0] ) === 'string'
         *      hent en flag på basis af værdierne i $args[0]
         *      $testFlag = new Flag( $key )
         */
        switch ( count( $args ) )
        {
            case 1 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'integer' :
                        $this->read( $args[0] ) ;
                        break ;
                    case 'string' :
                         if ( ! in_array( $args[0] , self::$allowedKeys ) )
                            throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , self::$allowedKeys ) . " ]" ) ;
                        $this->read( $args[0] ) ;
                        break ;
                    case 'array' :
                        /*
                         *  count( $args[0] ) === 2 : ny flag, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 2 :
                                $this->check( $args[0] ) ;
                                $this->values['flag_id'] = $this->create( $args[0] ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [2]" ) ;
                                break ;
                        }

                       foreach ( $args[0] as $key => $value ) 
                        { 
                            $this->values[$key] = $value ;
                        }   unset( $key , $value ) ;

                        break ;
                    default :
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,string,array]" ) ;
                        break ;
                }
                break ;
            default :
                throw new \Exception( count( $args ) . " : forkert antal parametre [1]" ) ;
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
