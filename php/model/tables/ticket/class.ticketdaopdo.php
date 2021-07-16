<?php namespace stader\model ;

/*

create table if not exists tickets
(
    ticket_id           int auto_increment primary key ,
    header              varchar(255) not null ,
    description         text not null ,
    assigned_place_id   int ,
        foreign key (assigned_place_id) references place(place_id)
        on update cascade 
        on delete restrict ,
    ticket_status_id    int not null ,
        foreign key (ticket_status_id) references ticket_status(ticket_status_id)
        on update cascade 
        on delete restrict ,
    assigned_user_id    int ,
        foreign key (assigned_user_id) references userscrypt(user_id)
        on update cascade 
        on delete restrict ,
    creationtime        datetime
        default   current_timestamp ,
    lastupdatetime       datetime
        default   current_timestamp
        on update current_timestamp ,
    active              boolean
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;
require_once( __dir__ . '/class.ticketlog.php' ) ;

class TicketDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class TicketDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }


    /*
     */
    public function create( Array $ticket )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $ticket ) ;

        $sql  = 'insert into tickets' ;
        $sql .= '        (  header ,  description ,  ticket_status_id ,  assigned_user_id ,  assigned_place_id ,  active )' ;
        $sql .= '    values' ;
        $sql .= '        ( :header , :description , :ticket_status_id , :assigned_user_id , :assigned_place_id , :active )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':header'            , $ticket['header']            , \PDO::PARAM_STR  ) ;
        $stmt->bindParam( ':description'       , $ticket['description']       , \PDO::PARAM_STR  ) ;
        $stmt->bindParam( ':ticket_status_id'  , $ticket['ticket_status_id']  , \PDO::PARAM_INT  ) ;
        $stmt->bindParam( ':assigned_place_id' , $ticket['assigned_place_id'] , \PDO::PARAM_INT  ) ;
        $stmt->bindParam( ':assigned_user_id'  , $ticket['assigned_user_id']  , \PDO::PARAM_INT  ) ;
        $stmt->bindParam( ':active'            , $ticket['active']            , \PDO::PARAM_BOOL ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $ticket['ticket_id'] = (int) $this->dbh->lastInsertId() ;

        $sql  = 'select * from tickets ' ;
        $sql .= 'where ticket_id = :ticket_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':ticket_id' , $ticket['ticket_id'] , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $this->WriteLog( $ticket['ticket_id'] , 'oprettet' , null , json_encode( $values ) ) ;

        $stmt = null ;
    return [ $ticket['ticket_id'] , $values['creationtime'] , $values['lastupdatetime'] ] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testTicket->read( 1 ) ;
     *      $testTicket->read( 'header' , 'Slettet' ) ;
     *      $testTicket->read( ['description'] , ['Anonymous'] ) ;
     *      $testTicket->read( ['header','description'] , ['Bjarne','BjarneDMat'] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                $sql  = 'select * from tickets ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from tickets ' ;
                        $sql .= 'where ticket_id = :ticket_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':ticket_id' , $args[0] , \PDO::PARAM_INT ) ;

                        $stmt->execute() ;
                        break ;
                    default: throw new \Exception('#argumnents error') ;
                }
                break ;
            case 2 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'string' :
                        $sql  = "select * from tickets " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from tickets " ;
                        $sql .= "where " ;
                        $where = [] ; 
                        foreach ( $args[0] as $key ) 
                            $where[] = "{$key} = ?" ;
                            unset( $key ) ;
                        $sql .= implode( ' and ' , $where ) ;

                        $stmt = $this->dbh->prepare( $sql ) ;

/*  J kunne ikke få dette her til at fungere i alle tilfælde
    Det fungerer når count($args[1]) == 1

                        foreach ( $args[1] as $key => $value )
                        {   // echo "stmt->bindParam( \":{$args[0][$key]}\" , {$value} , \PDO::PARAM_STR )" . PHP_EOL ;
                            $stmt->bindParam( ":{$args[0][$key]}" , $value , \PDO::PARAM_STR ) ; }
                            unset( $key , $value ) ;
 */
                       $stmt->execute( $args[1] ) ;
                       break ;
                    default: throw new \Exception('#argumnents error') ;
                }
                break ;
            default: throw new \Exception('#argumnents error') ;
        }

    return $stmt ; }

    public function readOne( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $stmt = $this->readData( ...$args ) ;
        if ( $stmt->rowCount() === 1 )
        {
            $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;
            foreach ( [ 'ticket_id' , 'ticket_status_id' , 'assigned_user_id' , 'assigned_place_id' ] as $key )
                $values[$key] = (int) $values[$key] ;
                unset( $key ) ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $ticketIds = [] ;
        $stmt = $this->readData( ...$args ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'ticket_id' )
            {
                while ( $ticket_id = $stmt->fetchColumn( $i ) )
                {
                    $ticketIds[] = (int) $ticket_id ;
                }
            break ; }
        }
    return $ticketIds ; }

    public function update( int $ticket_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $ticket_id , $key , $value ] ) ;

        $sql  = 'select * from tickets ' ;
        $sql .= 'where ticket_id = :ticket_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':ticket_id' , $ticket_id , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $sql  = 'update tickets ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where ticket_id = :ticket_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':ticket_id' , $ticket_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'     , $value   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;

        if ( $rowCount === 1 )
           {  $lastupdatetime = $this->WriteLog( $ticket_id , 'opdateret ' . $key , $values[$key] , $value ) ; }
        else
            { $lastupdatetime = ( new \DateTime() )->format( 'Y-m-d H:i:s' ) ; }

        $stmt = null ;
    return $lastupdatetime ; }

    public function delete( int $ticket_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $ticket_id ] ) ;

        $sql  = 'select * from tickets ' ;
        $sql .= 'where ticket_id = :ticket_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':ticket_id' , $ticket_id , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $sql  = 'delete from tickets ' ;
        $sql .= 'where ticket_id = :ticket_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':ticket_id' , $ticket_id , \PDO::PARAM_INT ) ;
        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

        $this->WriteLog( $ticket_id , 'slettet' , json_encode( $values ) , null ) ;

    return $rowCount ; }

    private function WriteLog( $ticket_id , $header , $oldValue , $newValue )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $ticket_id , $header ] ) ;

        require_once( dirname( __file__ , 2 ) . '/connect/class.setuplog.php' ) ;
        $setupLog  = new SetupLog( 'mysql' ) ;
        $ticketlog = new TicketLog
        ( 
            [
                'ticket_id' => $ticket_id ,
                'header'    => $header ,
                'old_value' => $oldValue ,
                'new_value' => $newValue
            ]
        ) ;

        $sql  = 'select * from tickets ' ;
        $sql .= 'where ticket_id = :ticket_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':ticket_id' , $ticket_id , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $stmt = null ;
    return $values['lastupdatetime'] ; }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->dbh = null ;
    }

}

?>
