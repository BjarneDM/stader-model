<?php namespace stader\model ;

/*

drop table if exists place ;
create table if not exists place
(
    place_id        int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int ,
        foreign key (place_owner_id) references place_owner(place_owner_id)
        on update cascade 
        on delete restrict ,
    area_id         int ,
        foreign key (area_id) references areas(area_id)
        on update cascade 
        on delete cascade ,
    lastchecked     datetime
        default   current_timestamp
        on update current_timestamp
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;
require_once( __dir__ . '/class.placelog.php' ) ;

class PlaceDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class PlaceDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;
        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }


    /*
     */
    public function create( Array $place )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $place ) ;

        $sql  = 'insert into places ' ;
        $sql .= '        (  place_nr ,  description ,  place_owner_id ,  area_id ,  active )' ;
        $sql .= '    values' ;
        $sql .= '        ( :place_nr , :description , :place_owner_id , :area_id , :active )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':place_nr'       , $place['place_nr']       , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':description'    , $place['description']    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':place_owner_id' , $place['place_owner_id'] , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':area_id'        , $place['area_id']        , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':active'         , $place['active']         , \PDO::PARAM_BOOL ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $place['place_id'] = (int) $this->dbh->lastInsertId() ;

        $sql  = 'select * from places ' ;
        $sql .= 'where place_id = :place_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':place_id' , $place['place_id'] , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $this->WriteLog( $place['place_id'] , "oprettet place" , null , json_encode( $values ) ) ;

        $stmt = null ;
    return [ $place['place_id'] , $values['lastchecked'] ] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testPlace->read( 1 ) ;
     *      $testPlace->read( 'alias' , 'Slettet' ) ;
     *      $testPlace->read( ['navn_for'] , ['Anonymous'] ) ;
     *      $testPlace->read( ['navn_for','alias'] , ['Bjarne','BjarneDMat'] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from places ' ;
                $sql .= ' order by place_nr asc ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from places ' ;
                        $sql .= 'where place_id = :place_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':place_id' , $args[0] , \PDO::PARAM_INT ) ;

                        $stmt->execute() ;
                        break ;
                    default: throw new \Exception('#argumnents error') ;
                }
                break ;
            case 2 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                // echo count($args ) . " : " . gettype( $args[1] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'string' :
                        $sql  = "select * from places " ;
                        $sql .= "where {$args[0]} = :{$args[0]} "  ;
                        $sql .= ' order by place_nr asc ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        switch ( gettype( $args[1] ) )
                        {
                            case 'integer' :
                                $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_INT ) ;
                                break ;
                            case 'string' :
                                $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;
                                break ;
                        }

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from places " ;
                        $sql .= "where " ;
                        $where = [] ; 
                        foreach ( $args[0] as $key ) 
                            $where[] = "{$key} = ?" ;
                            unset( $key ) ;
                        $sql .= implode( ' and ' , $where ) ;
                        $sql .= ' order by place_nr asc ' ;

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
            foreach ( [ 'place_id' , 'place_owner_id' , 'area_id' ] as $key )
                $values[$key] = (int) $values[$key] ;
                unset( $key ) ;
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
            if ( $columnMeta['name'] === 'place_id' )
            {
                while ( $place_id = $stmt->fetchColumn( $i ) )
                {
                    $placeIds[] = $place_id ;
                }
            break ; }
        }
    return $placeIds ; }

    public function update( int $place_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $place_id , $key , $value ] ) ;

        $sql  = 'select * from places ' ;
        $sql .= 'where place_id = :place_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':place_id' , $place_id , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $sql  = 'update places ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where place_id = :place_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':place_id' , $place_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'    , $value   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;

        $this->WriteLog( $place_id , "opdateret {$key}" , $values[ $key ] , $value ) ;

        $stmt = null ;
    }

    public function setChecked(  int $place_id , \DateTime $timestamp )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $place_id , $timestamp ] ) ;

        $lastchecked = $timestamp->format( 'Y-m-d H:i:s' ) ;

        $sql  = 'update places ' ;
        $sql .= 'set lastchecked = :lastchecked ' ;
        $sql .= 'where place_id = :place_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':place_id'    , $place_id      , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':lastchecked' , $lastchecked   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $this->WriteLog( $place_id , 'stade tjekket' , null , null ) ;
    }

    public function delete( int $place_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id ] ) ;


        $sql  = 'delete from places ' ;
        $sql .= 'where place_id = :place_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':place_id' , $place_id , \PDO::PARAM_INT ) ;
        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    private function WriteLog( $place_id , $header , $oldValue , $newValue )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $place_id , $header ] ) ;

        require_once( dirname( __file__ , 2 ) . '/connect/class.setuplog.php' ) ;
        $setupLog  = new SetupLog( 'mysql' ) ;
        $placelog = new PlaceLog
        ( 
            [
                'place_id' => $place_id ,
                'header'    => $header ,
                'old_value' => $oldValue ,
                'new_value' => $newValue
            ]
        ) ;

        $sql  = 'select * from places ' ;
        $sql .= 'where place_id = :place_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':place_id' , $place_id , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $stmt = null ;
    return $values['lastchecked'] ; }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->dbh = null ;
    }

}

?>
