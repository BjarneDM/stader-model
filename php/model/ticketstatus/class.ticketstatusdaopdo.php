<?php namespace stader\model ;

/*

create table if not exists ticket_status
(
    ticket_status_id    int auto_increment primary key,
    name                varchar(255) ,
        constraint unique (name) ,
    default_colour      varchar(255) ,
    description         text ,
    type_byte_id        int ,
        foreign key (type_byte_id) references type_byte(type_byte_id) ,
        on update cascade
        on delete restrict
)

 */
 
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class TicketStatusDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class TicketStatusDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }


    /*
     */
    public function create( Array $ticketstatus )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $ticketstatus ) ;

        $sql  = 'insert into ticket_status ' ;
        $sql .= '        (  name ,  default_colour ,  description ,  type_byte_id )' ;
        $sql .= '    values' ;
        $sql .= '        ( :name , :default_colour , :description , :type_byte_id )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( 'name'           , $ticketstatus['name']           , \PDO::PARAM_STR ) ;
        $stmt->bindParam( 'default_colour' , $ticketstatus['default_colour'] , \PDO::PARAM_STR ) ;
        $stmt->bindParam( 'description'    , $ticketstatus['description']    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( 'type_byte_id'   , $ticketstatus['type_byte_id']   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $ticketstatus['ticket_status_id'] = (int) $this->dbh->lastInsertId() ;

        $stmt = null ;
    return $ticketstatus['ticket_status_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testTicketStatus->read( 1 ) ;
     *      $testTicketStatus->read( 'alias' , 'Slettet' ) ;
     *      $testTicketStatus->read( ['navn_for'] , ['Anonymous'] ) ;
     *      $testTicketStatus->read( ['navn_for','alias'] , ['Bjarne','BjarneDMat'] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from ticket_status ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from ticket_status ' ;
                        $sql .= 'where ticket_status_id = :ticket_status_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':ticket_status_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from ticket_status " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from ticket_status " ;
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
            foreach ( [ 'ticket_status_id' , 'type_byte_id' ] as $key )
                $values[$key] = (int) $values[$key] ;
                unset( $key ) ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $ticketstatusIds = [] ;
        $stmt = $this->readData( ...$args ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'ticket_status_id' )
            {
                while ( $ticket_status_id = $stmt->fetchColumn( $i ) )
                {
                    $ticketstatusIds[] = (int) $ticket_status_id ;
                }
            break ; }
        }
    return $ticketstatusIds ; }

    public function update( int $ticket_status_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $ticket_status_id , $key , $value  ] ) ;

        $sql  = 'update ticket_status ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where ticket_status_id = :ticket_status_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':ticket_status_id' , $ticket_status_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'   , $value   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function delete( int $ticket_status_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id ] ) ;

        $sql  = 'delete from ticket_status ' ;
        $sql .= 'where ticket_status_id = :ticket_status_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':ticket_status_id' , $ticket_status_id , \PDO::PARAM_INT ) ;
        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->dbh = null ;
    }

}

?>
