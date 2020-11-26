<?php namespace stader\model ;
require_once( dirname( __file__ , 2 ) . '/class.classloader.php' ) ;

class MInstances
{

    public static $allowedClasses =
    [
        'Areas' , 'Places' , 'PlaceOwners' ,
        'Beredskabs' , 'Flags' , 'TypeBytes' ,
        'Groups' , 'Roles' ,
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

    public static function getObjects( ...$args )
    { 
        $className = strtolower( array_shift( $args ) ) ;
        switch ( $className )
        {

            // 'normale' classes
            case 'areas' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Areas() ;
                        break ;
                    case 2 :
                        return new \stader\model\Areas( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'beredskabs' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Beredskabs() ;
                        break ;
                    case 2 :
                        return new \stader\model\Beredskabs( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'flags' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Flags() ;
                        break ;
                    case 2 :
                        return new \stader\model\Flags( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'groups' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Groups() ;
                        break ;
                    case 2 :
                        return new \stader\model\Groups( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'places' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Places() ;
                        break ;
                    case 2 :
                        return new \stader\model\Places( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'placeowners' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\PlaceOwners() ;
                        break ;
                    case 2 :
                        return new \stader\model\PlaceOwners( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'roles' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Roles() ;
                        break ;
                    case 2 :
                        return new \stader\model\Roles( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'tickets' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Tickets() ;
                        break ;
                    case 2 :
                        return new \stader\model\Tickets( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'ticketsgroups' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\TicketsGroups() ;
                        break ;
                    case 2 :
                        return new \stader\model\TicketsGroups( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'ticketstatuses' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\TicketStatuses() ;
                        break ;
                    case 2 :
                        return new \stader\model\TicketStatuses( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'typebytes' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\TypeBytes() ;
                        break ;
                    case 2 :
                        return new \stader\model\TypeBytes( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'users' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\Users() ;
                        break ;
                    case 2 :
                        return new \stader\model\Users( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'usersberedskabs' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\UsersBeredskabs() ;
                        break ;
                    case 2 :
                        return new \stader\model\UsersBeredskabs( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'usersgroups' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\UsersGroups() ;
                        break ;
                    case 2 :
                        return new \stader\model\UsersGroups( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'usersroles' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\UsersRoles() ;
                        break ;
                    case 2 :
                        return new \stader\model\UsersRoles( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;

            // 'log' classes
            case 'beredskablogs' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\BeredskabLogs() ;
                        break ;
                    case 2 :
                        return new \stader\model\BeredskabLogs( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'ticketlogs' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\TicketLogs() ;
                        break ;
                    case 2 :
                        return new \stader\model\TicketLogs( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'placelogs' :
                switch ( count( $args ) )
                {
                    case 0 :
                        return new \stader\model\PlaceLogs() ;
                        break ;
                    case 2 :
                        return new \stader\model\PlaceLogs( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            default :
                throw new \Exception( "{$className} findes ikke" ) ;
                break ;
        }
    }

}

?>
