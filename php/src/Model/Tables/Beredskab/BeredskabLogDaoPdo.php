<?php namespace Stader\Model ;

/*

create table if not exists beredskab_log
(
    beredskab_log_id    int auto_increment primary key ,
    beredskab_id        int ,
        index (beredskab_id) ,
    header              varchar(255) ,
        index (header) ,
    log_timestamp       datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value           text default null ,
    new_value           text default null
) ;

 */
 
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class BeredskabLogDaoPdo implements ICrudDao
{

    private $dbh = null ;

    function __construct ( $connect )
    {   // echo 'class BeredskabLogDaoPdo implements ICrudDao __construct' . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;

    }


    /*
     */
    public function create( Array $beredskablog )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $beredskablog ) ;

        $sql  = 'insert into beredskab_log ' ;
        $sql .= '        (  header ,  beredskab_id ,  old_value ,  new_value )' ;
        $sql .= '    values' ;
        $sql .= '        ( :header , :beredskab_id , :old_value , :new_value )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':header'         , $beredskablog['header']       , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':old_value'      , $beredskablog['old_value']    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':new_value'      , $beredskablog['new_value']    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':beredskab_id'   , $beredskablog['beredskab_id'] , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1 - kunne ikke skrive i beredskab loggen' ) ;

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
                $sql  = 'select * from beredskab_log ' ;
                $sql .= "order by log_timestamp " ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from beredskab_log ' ;
                        $sql .= 'where beredskab_log_id = :beredskab_log_id ' ;
                        $sql .= "order by log_timestamp " ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':beredskab_log_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from beredskab_log " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from beredskab_log " ;
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
            $values['beredskab_log_id'] = (int) $values['beredskab_log_id'] ;
            $values['beredskab_id']     = (int) $values['beredskab_id'] ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $beredskabIds = [] ;
        $stmt = $this->readData( ...$args ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'beredskab_log_id' )
            {
                while ( $beredskab_log_id = $stmt->fetchColumn( $i ) )
                {
                    $beredskabIds[] = (int) $beredskab_log_id ;
                }
            break ; }
        }
    return $beredskabIds ; }

    public function update( int $beredskab_log_id , string $key , $value )
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
