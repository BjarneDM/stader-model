<?php namespace Stader\Model\Instance ;

class MInstances
{

    public static $allowedClasses =
    [
        'Areas' , 'Places' , 'PlaceOwners' ,
        'Beredskabs' , 'Flags' , 'TypeBytes' ,
        'UGroups' , 'URoles' ,
        'Tickets' ,  'TicketsGroups' , 'TicketStatuses' ,
        'Users' , 'UsersBeredskabs' , 'UsersGroups' , 'UsersRoles' 
    ] ;

    public static $allowedLogs =
    [
        'BeredskabLogs' , 'TicketLogs' , 'PlaceLogs'
    ] ;

//     function __construct()
//     {   // echo 'class MInstances __construct' . \PHP_EOL ;
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
            case 0 :
                return new $className() ;
                break ;
            case 2 :
                return new $className( $args[0] , $args[1] ) ;
                break ;
        }
    }

}

?>
