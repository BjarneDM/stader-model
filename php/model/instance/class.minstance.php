<?php namespace stader\model ;
require_once( dirname( __file__ , 2 ) . '/class.classloader.php' ) ;

class MInstance
{

    public static $allowedClasses =
    [
        'Area' , 'Place' , 'PlaceOwner' ,
        'Beredskab' , 'Flag' , 'TypeByte' ,
        'Group' , 'Role' ,
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
    {
        $className =  '\\stader\\model\\' . array_shift( $args ) ;
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

    public static function getObjectOld( ...$args )
    { 
        $className = strtolower( array_shift( $args ) ) ;
        switch ( $className )
        {

            // 'normale' classes
            case 'area' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\Area( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\Area( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'beredskab' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\Beredskab( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\Beredskab( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'flag' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\Flag( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\Flag( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'group' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\Group( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\Group( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'place' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\Place( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\Place( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'placeowner' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\PlaceOwner( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\PlaceOwner( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'role' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\Role( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\Role( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'ticket' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\Ticket( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\Ticket( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'ticketgroup' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\TicketGroup( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\TicketGroup( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'ticketstatus' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\TicketStatus( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\TicketStatus( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'typebyte' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\TypeByte( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\TypeByte( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'user' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\User( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\User( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'userberedskab' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\UserBeredskab( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\UserBeredskab( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'usergroup' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\UserGroup( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\UserGroup( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'userrole' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\UserRole( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\UserRole( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'beredskabLog' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\BeredskabLog( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\BeredskabLog( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;
            case 'ticketlog' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\TicketLog( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\TicketLog( $args[0] , $args[1] ) ;
                        break ;
                }
                break ;

            // 'log' classes
            case 'placelog' :
                switch ( count( $args ) )
                {
                    case 1 :
                        return new \stader\model\PlaceLog( $args[0] ) ;
                        break ;
                    case 2 :
                        return new \stader\model\PlaceLog( $args[0] , $args[1] ) ;
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
