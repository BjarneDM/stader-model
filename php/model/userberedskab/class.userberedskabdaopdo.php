<?php namespace stader\model ;

/*

create table if not exists user_beredskab
(
    user_beredskab_id   int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete cascade ,
    beredskab_id        int ,
        foreign key (beredskab_id) references beredskab(beredskab_id)
        on update cascade 
        on delete cascade
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class UserBeredskabDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;

    function __construct ( $connect )
    {   // echo 'class GroupDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // print_r( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;

    }

    /*
     */
    public function create( Array $ids )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $ids ) ;

        $sql  = 'insert into user_beredskab' ;
        $sql .= '        (  user_id ,  beredskab_id )' ;
        $sql .= '    values' ;
        $sql .= '        ( :user_id , :beredskab_id )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':user_id'  , $ids['user_id']  , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':beredskab_id' , $ids['beredskab_id'] , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $ids['user_beredskab_id'] = (int) $this->dbh->lastInsertId() ;

        $stmt = null ;
    return $ids['user_beredskab_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testGroup->read( user_beredskab_id ) ;
     *      $testGroup->read( 'user_id' , user_id ) ;
     *      $testGroup->read( ['user_id'] , [user_id] ) ;
     *      $testGroup->read( ['user_id','beredskab_id'] , [user_id,beredskab_id] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from user_beredskab ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from user_beredskab ' ;
                        $sql .= 'where user_beredskab_id = :user_beredskab_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':user_beredskab_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from user_beredskab " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from user_beredskab " ;
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
            foreach ( [ 'user_beredskab_id' , 'user_id' , 'beredskab_id' ] as $key )
                $values[$key] = (int) $values[$key] ;
                unset( $key ) ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $stmt = $this->readData( ...$args ) ;
        $usersgroupsIds = [] ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'user_beredskab_id' )
            {
                while ( $user_beredskab_id = $stmt->fetchColumn( $i ) )
                {
                    $usersgroupsIds[] = (int) $user_beredskab_id ;
                }
            break ; }
        }
    return $usersgroupsIds ; }

    public function update( int $user_beredskab_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $user_beredskab_id , $key , $value ] ) ;

        $sql  = 'update user_beredskab ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where user_beredskab_id = :user_beredskab_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':user_beredskab_id' , $user_beredskab_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'           , $value           , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function delete( int $user_beredskab_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $sql  = 'delete from user_beredskab ' ;
        $sql .= 'where users_beredskab_id = :user_beredskab_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':user_beredskab_id' , $user_beredskab_id , \PDO::PARAM_INT ) ;
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
