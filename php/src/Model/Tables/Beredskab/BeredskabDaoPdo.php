<?php namespace Stader\Model ;

/*

create table if not exists beredskab
(
    beredskab_id    int auto_increment primary key ,
    message         text not null ,
    header          text ,
    created_by_id   int not null ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete restrict ,
    active          boolean default true 
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;
require_once( __dir__ . '/class.beredskablog.php' ) ;

class BeredskabDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class BeredskabDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }


    /*
     */
    public function create( Array $beredskab )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $beredskab ) ;

        switch ( count( $beredskab ) )
        {
            case 3 :
                $sql  = 'insert into beredskab' ;
                $sql .= '        (  message ,  header ,  created_by_id  )' ;
                $sql .= '    values' ;
                $sql .= '        ( :message , :header , :created_by_id  )' ;

                $stmt = $this->dbh->prepare( $sql ) ;

                $stmt->bindParam( ':message'        , $beredskab['message']         , \PDO::PARAM_STR  ) ;
                $stmt->bindParam( ':header'         , $beredskab['header']          , \PDO::PARAM_STR  ) ;
                $stmt->bindParam( ':created_by_id'  , $beredskab['created_by_id']   , \PDO::PARAM_INT  ) ;

                break ;
            case 6 :
                $sql  = 'insert into beredskab' ;
                $sql .= '        (   message ,  header ,  created_by_id ,  flag ,  colour ,  active )' ;
                $sql .= '    values' ;
                $sql .= '        (  :message , :header , :created_by_id , :flag , :colour , :active )' ;

                $stmt = $this->dbh->prepare( $sql ) ;

                $stmt->bindParam( ':message'        , $beredskab['message']         , \PDO::PARAM_STR   ) ;
                $stmt->bindParam( ':header'         , $beredskab['header']          , \PDO::PARAM_STR   ) ;
                $stmt->bindParam( ':created_by_id'  , $beredskab['created_by_id']   , \PDO::PARAM_INT   ) ;
                $stmt->bindParam( ':flag'           , $beredskab['flag']            , \PDO::PARAM_STR   ) ;
                $stmt->bindParam( ':colour'         , $beredskab['colour']          , \PDO::PARAM_STR   ) ;
                $stmt->bindParam( ':active'         , $beredskab['active']          , \PDO::PARAM_BOOL  ) ;

                break ;
        }

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $beredskab['beredskab_id'] = (int) $this->dbh->lastInsertId() ;

        $sql  = 'select * from beredskab ' ;
        $sql .= 'where beredskab_id = :beredskab_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':beredskab_id' , $beredskab['beredskab_id'] , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $this->WriteLog( $beredskab['beredskab_id'] , 'oprettet' , null , json_encode( $values ) ) ;

        $stmt = null ;
    return $beredskab['beredskab_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testBeredskab->read( 1 ) ;
     *      $testBeredskab->read( 'header' , 'Slettet' ) ;
     *      $testBeredskab->read( ['description'] , ['Anonymous'] ) ;
     *      $testBeredskab->read( ['header','description'] , ['Bjarne','BjarneDMat'] ) ;
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
                $sql  = 'select * from beredskab ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from beredskab ' ;
                        $sql .= 'where beredskab_id = :beredskab_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':beredskab_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from beredskab " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from beredskab " ;
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
            foreach ( [ 'beredskab_id' , 'created_by_id' ] as $key )
                $values[$key] = (int) $values[$key] ;
                unset( $key ) ;
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
            if ( $columnMeta['name'] === 'beredskab_id' )
            {
                while ( $beredskab_id = $stmt->fetchColumn( $i ) )
                {
                    $beredskabIds[] = (int) $beredskab_id ;
                }
            break ; }
        }
    return $beredskabIds ; }

    public function update( int $beredskab_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $beredskab_id , $key , $value ] ) ;

        $sql  = 'select * from beredskab ' ;
        $sql .= 'where beredskab_id = :beredskab_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':beredskab_id' , $beredskab_id , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $sql  = 'update beredskab ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where beredskab_id = :beredskab_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':beredskab_id' , $beredskab_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'     , $value   , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;

        if ( $rowCount === 1 )
           {  $lastupdatetime = $this->WriteLog( $beredskab_id , 'opdateret ' . $key , $values[$key] , $value ) ; }
        else
            { $lastupdatetime = ( new \DateTime() )->format( 'Y-m-d H:i:s' ) ; }

        $stmt = null ;
    return $lastupdatetime ; }

    public function delete( int $beredskab_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $beredskab_id ] ) ;

        $sql  = 'select * from beredskab ' ;
        $sql .= 'where beredskab_id = :beredskab_id ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':beredskab_id' , $beredskab_id , \PDO::PARAM_INT  ) ;
        $stmt->execute() ;
        $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $sql  = 'delete from beredskabs ' ;
        $sql .= 'where beredskab_id = :beredskab_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':beredskab_id' , $beredskab_id , \PDO::PARAM_INT ) ;
        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

        $this->WriteLog( $beredskab_id , 'slettet' , json_encode( $values ) , null ) ;

    return $rowCount ; }

    private function WriteLog( $beredskab_id , $header , $oldValue , $newValue )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $beredskab_id , $header ] ) ;

        require_once( dirname( __file__ , 2 ) . '/connect/class.setuplog.php' ) ;
        $setupLog  = new SetupLog( 'mysql' ) ;
        $beredskablog = new BeredskabLog
        ( 
            [
                'beredskab_id'  => $beredskab_id ,
                'header'        => $header ,
                'old_value'     => $oldValue ,
                'new_value'     => $newValue
            ]
        ) ;

        $stmt = null ;
    }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->dbh = null ;
    }

}

?>
