<?php namespace stader\model ;

/*

INPUT :
create table users
(
    user_id     int auto_increment primary key , <- denne bliver genereret af DB
    name        varchar(255) not null ,          <- de resterende felter er krævede
    surname     varchar(255) default '' ,
    phone       varchar(255) not null ,
    username    varchar(255) not null ,
        constraint unique (username) ,
    passwd      varchar(255) not null ,
    email       varchar(255) not null ,
        constraint unique (email) ,
) engine = memory ;

OUTPUT :
create table usersCrypt
(
    user_id     int primary key ,
    salt        varchar(255) ,
    algo        varchar(255) ,
    tag         varchar(255) ,
    data        text
) ;

 */

require_once( __dir__ . '/class.usercryptdao.php' ) ;

class UserCrypt extends UserCryptDao
{

    function __construct ( $connect , $input )
    {   // echo 'class UserCrypt extends UserCryptDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        parent::__construct( $connect ) ;

        /*
         *  gettype( $input ) === 'integer' 
         *      opret en User på basis af et user_id
         *      $testUser = new User( user_id ) ;
         *  gettype( $input ) === 'array'
         *      opret en user på basis af værdierne i $input
         *      $testUser = new UserCrypt( $newUser )
         */
        switch ( strtolower( gettype( $input ) ) )
        {
            case 'integer' :
                $this->read( $input ) ;
                break ;
            case 'array' :
                /*
                 *  count( $input ) === 7 : ny user, der skal oprettes
                 */
                switch ( count( $input ) )
                {
                    case 7 :
                        $this->create( $input ) ;
                        break ;
                    default :
                        throw new \Exception( count( $input ) . " : forkert antal parametre [7]" ) ;
                        break ;
                }

                break ;
            default :
                throw new \Exception( gettype( $input ) . " : forkert input type [integer,array]" ) ;
                break ;
        }
    }

    public function __get( $property ) 
    {   // echo '__get(' . $property . ')' . \PHP_EOL ;
        // print_r( $this->values ) ;

        /*
            thjek om $property findes som key i $values
         */
        if ( isset( $this->values[ $property ] ) )
        {
            return $this->values[ $property ] ;
        } else {
            throw new \Exception( "{$property} doesn't exist" ) ;
        }
    }

//     public function __set( $property , $value )
//     {   // echo '__set(' . $property . ' : ' . $value . ')' . \PHP_EOL ;
// 
//         /*
//             tjek om $property findes som key i $values
//          */
//         if ( isset( $this->values[ $property ] ) )
//         {
//             /*
//                 sæt værdiwn både i objektet & opdater databasen
//              */
//             $this->values[ $property ] = $value ;
//             $this->update( $property , $value ) ;
//             return true ; 
//         } else {
//             throw new \Exception( "{$property} doesn't exist" ) ;
//         }
//     }

}

?>
