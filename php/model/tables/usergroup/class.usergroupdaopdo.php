<?php namespace stader\model ;

/*

create table users_groups
(
    users_groups_id     int auto_increment primary key ,
    user_id           int ,
        foreign key (user_id) references users(user_id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references user_groups(group_id)
        on update cascade 
        on delete cascade
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class UserGroupDaoPdo implements ICrudDao
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

        $sql  = 'insert into users_groups' ;
        $sql .= '        (  user_id ,  group_id )' ;
        $sql .= '    values' ;
        $sql .= '        ( :user_id , :group_id )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':user_id'  , $ids['user_id']  , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':group_id' , $ids['group_id'] , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $ids['users_groups_id'] = (int) $this->dbh->lastInsertId() ;

        $stmt = null ;
    return $ids['users_groups_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testGroup->read( user_group_id ) ;
     *      $testGroup->read( 'user_id' , user_id ) ;
     *      $testGroup->read( ['user_id'] , [user_id] ) ;
     *      $testGroup->read( ['user_id','group_id'] , [user_id,group_id] ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select * from users_groups ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from users_groups ' ;
                        $sql .= 'where users_groups_id = :users_groups_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':users_groups_id' , $args[0] , \PDO::PARAM_INT ) ;

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
                        $sql  = "select * from users_groups " ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        $stmt->execute() ;
                        break ;
                    case 'array' :
                        $sql  = "select * from users_groups " ;
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
            foreach ( [ 'users_groups_id' , 'user_id' , 'group_id' ] as $key )
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
            if ( $columnMeta['name'] === 'users_groups_id' )
            {
                while ( $users_groups_id = $stmt->fetchColumn( $i ) )
                {
                    $usersgroupsIds[] = (int) $users_groups_id ;
                }
            break ; }
        }
    return $usersgroupsIds ; }

    public function update( int $users_groups_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $users_groups_id , $key , $value ] ) ;

        $sql  = 'update users_groups ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where users_groups_id = :users_groups_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':users_groups_id' , $users_groups_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'           , $value           , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function delete( int $users_groups_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $sql  = 'delete from users_groups ' ;
        $sql .= 'where users_groups_id = :users_groups_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':users_groups_id' , $users_groups_id , \PDO::PARAM_INT ) ;
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
