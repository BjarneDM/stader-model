<?php namespace stader\model ;

/*

create table if not exists place_log
(
    place_log_id    int auto_increment primary key ,
    header          varchar(255) ,
    full_place      varchar(8) ,
        index (full_place) ,
    log_timestamp   datetime
        default current_timestamp ,
        index (log_timestamp) ,
    data            text
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;
require_once( dirname( __file__ , 2 ) . '/area/class.area.php' ) ;

class PlaceLogDaoPdo implements ICrudDao
{

    private $dbh = null ;

    function __construct ( $connect )
    {   // echo 'class PlaceLogDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;
        $this->dbh = $connect->getConn() ;

    }


    /*
     */
    public function create( Array $placelog )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $placelog ) ;

        $sql  = 'insert into place_log ' ;
        $sql .= '        (  header ,  place_id ,  old_value ,  new_value )' ;
        $sql .= '    values' ;
        $sql .= '        ( :header , :place_id , :old_value , :new_value )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':header'    , $placelog['header']    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':old_value' , $placelog['old_value'] , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':new_value' , $placelog['new_value'] , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':place_id'  , $placelog['place_id']  , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1 - kunne ikke skrive i place loggen') ;

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
                $sql  = 'select * from place_log ' ;
                $sql .= "order by log_timestamp " ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from place_log ' ;
                        $sql .= 'where place_log_id = :place_log_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':place_log_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from place_log " ;
                        $sql .= "where {$args[0]} = ? " ;
                        $sql .= "order by log_timestamp " ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->execute( [ $args[1] ]) ;
                        break ;
                    case 'array' :
                        $sql  = "select * from place_log " ;
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
            $values['place_id'] = (int) $values['place_id'] ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $placeIds = [] ;
        $stmt = $this->readData( ...$args ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'place_log_id' )
            {
                while ( $place_log_id = $stmt->fetchColumn( $i ) )
                {
                    $placeIds[] = (int) $place_log_id ;
                }
            break ; }
        }
    return $placeIds ; }

    public function update( int $place_log_id , string $key , $value )
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
