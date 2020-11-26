<?php namespace stader\model ;

/*

create table if not exists flags
(
    flag_id     int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class FlagDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class FlagDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;
        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }


    /*
     */
    public function create( Array $flag )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $flag ) ;


        $sql  = 'insert into flags' ;
        $sql .= '        (  text ,  unicode )' ;
        $sql .= '    values' ;
        $sql .= '        ( :text , :unicode )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':text'       , $flag['text']     , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':unicode'    , $flag['unicode']  , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $flag['flag_id'] = (int) $this->dbh->lastInsertId() ;

        $stmt = null ;
    return $flag['flag_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testFlag->read( 1 ) ;
     *      $testFlag->read( 'alias' , 'Slettet' ) ;
     *      $testFlag->read( ['navn_for'] , ['Anonymous'] ) ;
     *      $testFlag->read( ['navn_for','alias'] , ['Bjarne','BjarneDMat'] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from flags ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from flags ' ;
                        $sql .= 'where flag_id = :flag_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':flag_id' , $args[0] , \PDO::PARAM_INT ) ;

                        $stmt->execute() ;
                        break ;
                    case 'string' :
                        $sql  = 'select * from flags ' ;
                        $sql .= 'where text = :text ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':text' , $args[0] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
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
            $values['flag_id'] = (int) $values['flag_id'] ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $flagIds = [] ;
        $stmt = $this->readData() ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'flag_id' )
            {
                while ( $flag_id = $stmt->fetchColumn( $i ) )
                {
                    $flagIds[] = (int) $flag_id ;
                }
            break ; }
        }
    return $flagIds ; }

    public function update( int $flag_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $flag_id , $key , $value ] ) ;


        $sql  = 'update flags ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where flag_id = :flag_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':flag_id' , $flag_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'   , $value   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function delete( int $flag_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id ] ) ;

        $sql  = 'delete from flags ' ;
        $sql .= 'where flag_id = :flag_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':flag_id' , $flag_id , \PDO::PARAM_INT ) ;
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
