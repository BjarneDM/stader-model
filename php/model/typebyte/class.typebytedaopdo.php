<?php namespace stader\model ;

/*

create table in not exists type_byte
(
    type_byte_id        int auto_increment primary key ,
    name                varchar(255) ,
        constraint unique (name)
)

 */
 
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class TypeByteDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class TypeByteDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;
        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }


    /*
     */
    public function create( Array $typebyte )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $typebyte ) ;

        $sql  = 'insert into type_byte ' ;
        $sql .= '        (  name )' ;
        $sql .= '    values' ;
        $sql .= '        ( :name )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( 'name' , $typebyte['name'] , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $typebyte['type_byte_id'] = (int) $this->dbh->lastInsertId() ;

        $stmt = null ;
    return $typebyte['type_byte_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testTypeByte->read( 1 ) ;
     *      $testTypeByte->read( 'alias' , 'Slettet' ) ;
     *      $testTypeByte->read( ['navn_for'] , ['Anonymous'] ) ;
     *      $testTypeByte->read( ['navn_for','alias'] , ['Bjarne','BjarneDMat'] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from type_byte ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from type_byte ' ;
                        $sql .= 'where type_byte_id = :type_byte_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':type_byte_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from type_byte " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from type_byte " ;
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
            $values['type_byte_id'] = (int) $values['type_byte_id'] ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $typebyteIds = [] ;
        $stmt = $this->readData( ...$args ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'type_byte_id' )
            {
                while ( $type_byte_id = $stmt->fetchColumn( $i ) )
                {
                    $typebyteIds[] = (int) $type_byte_id ;
                }
            break ; }
        }
    return $typebyteIds ; }

    public function update( int $type_byte_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $type_byte_id , $key , $value ] ) ;

        $sql  = 'update type_byte ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where type_byte_id = :type_byte_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':type_byte_id' , $type_byte_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'   , $value   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function delete( int $type_byte_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id ] ) ;

        $sql  = 'delete from type_byte ' ;
        $sql .= 'where type_byte_id = :type_byte_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':type_byte_id' , $type_byte_id , \PDO::PARAM_INT ) ;
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
