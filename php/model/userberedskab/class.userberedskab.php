<?php namespace stader\model ;

/*

create table if not exists user_beredskab
(
    user_beredskab_id   int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete cascade ,
    beredskab_id        int ,
        foreign key (group_id) references beredskab(beredskab_id)
        on update cascade 
        on delete cascade
) ;

 */

require_once( __dir__ . '/class.userberedskabdao.php' ) ;

class UserBeredskab extends UserBeredskabDao
{
    private $allowedKeys = [ 'user_id' , 'username' , 'email' , 'beredskab_id' ] ;

    function __construct ( ...$args )
    {   // echo 'class UserBeredskab extends UserBeredskabDao' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        // var_dump( $args ) ;
        /*
         *  gettype( $args[0] ) === 'integer' 
         *      henbt en UserBeredskab på basis af et user_beredskab_id
         *      $testUserBeredskab = new UserBeredskab( user_beredskab_id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en user på basis af værdierne i $args[0]
         *      $testUserBeredskab = new UserBeredskab( $newUserBeredskab )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent en user på basis af værdierne i $args[0],$args[1]
         *      $testUserBeredskab = new UserBeredskab( $keys , $values )
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
                         *  count( $args[0] ) === 2 : ny UserBeredskab, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 2 :
                                $this->check( $args[0] ) ;
                                $this->values['user_beredskab_id'] = $this->create( $args[0] ) ;
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
                        throw new \Exception( gettype( $args[0] ) . " : forkert input type [integer,array]" ) ;
                        break ;
                }
                break ;
            case 2 :
                switch ( strtolower( gettype( $args[0] ) ) )
                {
                    case 'string' :
                         if ( ! in_array( $args[0] , $this->allowedKeys ) )
                            throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
                        break ;
                    case 'array' :
                        if ( count( $args[0] ) !== count( $args[1] ) )
                            throw new \Exception( 'count() for $args[0] & $args[1] er forskellige' ) ;
                        foreach ( $args[0] as $key )
                        {
                            if ( ! in_array( $key , $this->allowedKeys ) )
                                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
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
            if ( ! in_array( $key , $this->allowedKeys ) )
                throw new \Exception( "'{$key}' doesn't exist in [ " . implode( ' , ' , $this->allowedKeys ) . " ]" ) ;
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
