<?php namespace Stader\Model\Instance ;

class MInstance
{

    public static $allowedClasses =
    [
        'Area' , 'Place' , 'PlaceOwner' ,
        'Beredskab' , 'Flag' , 'TypeByte' ,
        'UGroup' , 'URole' ,
        'Ticket' ,  'TicketGroup' , 'TicketStatus' ,
        'User' , 'UserBeredskab' , 'UserGroup' , 'UserRole' 
    ] ;

    public static $allowedLogs =
    [
        'BeredskabLog' , 'TicketLog' , 'PlaceLog'
    ] ;

//     function __construct()
//     {   // echo 'class MInstance __construct' . \PHP_EOL ;
//         // print_r( $args ) ;
//     }

    public static function getObject( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $className = array_shift( $args ) ;
        exec( "find '".dirname(__DIR__)."/Tables' -type f -name '{$className}.php' " , $classFile ) ;
        $classFile = preg_split( '/[\/\.]/' , $classFile[0] ) ;
        $classFile = array_slice( $classFile , array_search( 'src', $classFile , true ) ) ;
        $classFile[0] = 'Stader' ;
        unset( $classFile[array_key_last($classFile)] ) ;
        $className = implode( '\\' , $classFile ) ;

        switch ( count( $args ) )
        {
            case 1 :
                return new $className( $args[0] ) ;
                break ;
            case 2 :
                return new $className( $args[0] , $args[1] ) ;
                break ;
        }
    }

}

?>
