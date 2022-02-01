<?php namespace Stader\Model ;

/*

create table if not exists ticket_log
(
    ticket_log_id    int auto_increment primary key ,
    header          varchar(255) ,
    ticket_id       int ,
    log_timestamp   datetime
        default current_timestamp ,
    data            text
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class TicketLogDaoPdo implements ICrudDao
{

    private $dbh = null ;

    function __construct ( $connect )
    {   // echo 'class TicketLogDaoPdo implements ICrudDao __construct' . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;

    }


    /*
     */
    public function create( Array $ticketlog )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $ticketlog ) ;

        $sql  = 'insert into ticket_log ' ;
        $sql .= '        (  header ,  ticket_id ,  old_value ,  new_value )' ;
        $sql .= '    values' ;
        $sql .= '        ( :header , :ticket_id , :old_value , :new_value )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':header'    , $ticketlog['header']    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':old_value' , $ticketlog['old_value'] , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':new_value' , $ticketlog['new_value'] , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':ticket_id' , $ticketlog['ticket_id'] , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1 - kunne ikke skrive i ticket loggen' ) ;

        $stmt = null ;
    }

    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from ticket_log ' ;
                $sql .= "order by log_timestamp " ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from ticket_log ' ;
                        $sql .= 'where ticket_log_id = :ticket_log_id ' ;
                        $sql .= "order by log_timestamp " ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':ticket_log_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from ticket_log " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from ticket_log " ;
                        $sql .= "where " ;
                        $where = [] ; 
                        foreach ( $args[0] as $key ) 
                            $where[] = "{$key} = ?" ;
                            unset( $key ) ;
                        $sql .= implode( ' and ' , $where ) ;
                        $sql .= " order by log_timestamp " ;

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
            $values['ticket_log_id'] = (int) $values['ticket_log_id'] ;
            $values['ticket_id']     = (int) $values['ticket_id'] ;
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
            if ( $columnMeta['name'] === 'ticket_log_id' )
            {
                while ( $ticket_log_id = $stmt->fetchColumn( $i ) )
                {
                    $ticketIds[] = (int) $ticket_log_id ;
                }
            break ; }
        }
    return $ticketIds ; }

    public function update( int $ticket_log_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        throw new \Exception( __file__ . " : " . __function__ . ' er ikke implementeret' ) ;
    }

    public function delete( int $id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        throw new \Exception( __file__ . " : " . __function__ . ' er ikke implementeret' ) ;
    }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->dbh = null ;
    }

}

?>
