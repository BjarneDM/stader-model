<?php namespace stader\model ;

/*

create table if not exists beredskab
(
    beredskab_id    int auto_increment primary key ,
    message         text not null ,
    note            text ,
    created_by_id   int not null ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete restrict ,
    active          boolean default true 
) ;

 */

require_once( __dir__ . '/class.beredskabdao.php' ) ;

class Beredskab extends BeredskabDao
{
    public static $allowedKeys = [ 'message' , 'header' , 'created_by_id' , 'flag' , 'colour' , 'active' ] ;

    function __construct ( ...$args )
    {   // echo 'class Beredskab extends BeredskabDao __construct' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        /*
         *  gettype( $args[0] ) === 'integer' 
         *      henbt en Beredskab på basis af et beredskab_id
         *      $testBeredskab = new Beredskab( beredskab_id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en beredskab på basis af værdierne i $args[0]
         *      $testBeredskab = new Beredskab( $newBeredskab )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent en beredskab på basis af værdierne i $args[0],$args[1]
         *      $testBeredskab = new Beredskab( $keys , $values )
         */
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
                         *  count( $args[0] ) === 3 / 6 : ny beredskab, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 3 :
                            case 6 :
                                $this->check( $args[0] ) ;
                                    $this->values['beredskab_id'] = $this->create( $args[0] ) ;
                                break ;
                            default :
                                throw new \Exception( count( $args[0] ) . " : forkert antal parametre [3,6]" ) ;
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

//             switch ( $key )
//             {
//                 case 'assigned_user_id' :
//                     if ( in_array( $toCheck[ $key ] , [ '' , null ] ) )
//                         $toCheck[ $key ] = ( new User( 'name' , 'dummy' ) )->getData()[ $key ] ;
//                 break ;
//             }
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

    public function switchOff()
    {
        $this->update( 'active' , false ) ;        
    }

}

?>
