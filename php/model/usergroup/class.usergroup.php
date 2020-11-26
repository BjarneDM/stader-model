<?php namespace stader\model ;

/*

create table users_groups
(
    users_groups_id     int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references users(user_id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references user_groups(group_id)
        on update cascade 
        on delete cascade
) ;

 */

require_once( __dir__ . '/class.usergroupdao.php' ) ;

class UserGroup extends UserGroupDao
{
    private $allowedKeys = [ 'users_groups_id' , 'user_id' , 'group_id' ] ;

    function __construct ( ...$args )
    {   // echo 'class UserGroupGroup extends UserGroupGroupDao' . \PHP_EOL ;
        // print_r( $args ) ;

        parent::__construct() ;

        // var_dump( $args ) ;
        /*
         *  gettype( $args[0] ) === 'integer' 
         *      henbt en UserGroup på basis af et users_groups_id
         *      $testUserGroup = new UserGroup( users_groups_id ) ;
         *  gettype( $args[0] ) === 'array'
         *      opret en user på basis af værdierne i $args[0]
         *      $testUserGroup = new UserGroup( $newUserGroup )
         *  gettype( $args[0] ) === ['string','array'] , gettype( $args[1] ) === ['string','array']
         *      hent en user på basis af værdierne i $args[0],$args[1]
         *      $testUserGroup = new UserGroup( $keys , $values )
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
                         *  count( $args[0] ) === 2 : ny user, der skal oprettes
                         */
                        switch ( count( $args[0] ) )
                        {
                            case 2 :
                                $this->check( $args[0] ) ;
                                $this->values['users_groups_id'] = $this->create( $args[0] ) ;
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
