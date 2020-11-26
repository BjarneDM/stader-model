<?php namespace stader\model ;

/*

create table place_owner
(
    place_owner_id  int auto_increment primary key ,
    name            varchar(255) not null ,
    surname         varchar(255) not null ,
    phone           varchar(255) not null ,
    email           varchar(255) not null ,
    organisation    varchar(255) not null
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class PlaceOwnerDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class PlaceOwnerDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;
        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }


    /*
     */
    public function create( Array $placeowner )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $placeowner ) ;


        $sql  = 'insert into place_owner' ;
        $sql .= '        (  name ,  surname ,  phone ,  email ,  organisation )' ;
        $sql .= '    values' ;
        $sql .= '        ( :name , :surname , :phone , :email , :organisation )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':name'         , $placeowner['name']         , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':surname'      , $placeowner['surname']      , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':phone'        , $placeowner['phone']        , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':email'        , $placeowner['email']        , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':organisation' , $placeowner['organisation'] , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $placeowner['place_owner_id'] = (int) $this->dbh->lastInsertId() ;

        $stmt = null ;
    return $placeowner['place_owner_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testPlaceOwner->read( 1 ) ;
     *      $testPlaceOwner->read( 'header' , 'Slettet' ) ;
     *      $testPlaceOwner->read( ['description'] , ['Anonymous'] ) ;
     *      $testPlaceOwner->read( ['header','description'] , ['Bjarne','BjarneDMat'] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from place_owner ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from place_owner ' ;
                        $sql .= 'where place_owner_id = :place_owner_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':place_owner_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from place_owner " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from place_owner " ;
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
            $values['place_owner_id'] = (int) $values['place_owner_id'] ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $placeownerIds = [] ;
        $stmt = $this->readData( ...$args ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'place_owner_id' )
            {
                while ( $place_owner_id = $stmt->fetchColumn( $i ) )
                {
                    $placeownerIds[] = (int) $place_owner_id ;
                }
            break ; }
        }
    return $placeownerIds ; }

    public function update( int $place_owner_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $place_owner_id , $key , $value ] ) ;


        $sql  = 'update place_owner ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where place_owner_id = :place_owner_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':place_owner_id' , $place_owner_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'   , $value   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function delete( int $place_owner_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id ] ) ;


        $sql  = 'delete from place_owner ' ;
        $sql .= 'where place_owner_id = :place_owner_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':place_owner_id' , $place_owner_id , \PDO::PARAM_INT ) ;
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
