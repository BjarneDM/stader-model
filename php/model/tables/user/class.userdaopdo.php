<?php namespace stader\model ;

/*

create table user
(
    user_id     int auto_increment primary key , <- denne bliver genereret af DB
    name        varchar(255) not null ,          <- de resterende felter er krævede
    surname     varchar(255) default '' ,
    phone       varchar(255) not null ,
    username    varchar(255) not null ,
        constraint unique (username) ,
    passwd      varchar(255) not null ,
    email       varchar(255) not null ,
        constraint unique (email) ,
) engine = memory ;

 */

require_once( dirname( __file__ , 3 ) . '/interfaces/interface.cruddao.php' ) ;

class UserDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private static $connect = null ;
    private $userTable = '' ;

    function __construct ( $connect )
    {   // echo 'class UserDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;
        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;
        $this->initTable() ;
    }

    /*
     */
    public function initTable()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $sql  = [] ;
        $stmt = [] ;

        // https://stackoverflow.com/questions/8829102/check-if-table-exists-without-using-select-from
        /*
         * opret en DB cache for user
         * tjek om tabellen $user findes i databasen 'staderdata'
         */
        $sql[0]  = 'select *  ' ;
        $sql[0] .= 'from information_schema.tables ' ;
        $sql[0] .= 'where   table_schema = "staderdata"  ' ;
        $sql[0] .= '    and table_name = :users ' ;
        $sql[0] .= 'limit 1  ' ;

        $stmt[0] = $this->dbh->prepare( $sql[0] ) ;

        $randomStr = new RandomStr( [ 'length' => 24 , 'ks' => 0 ] ) ;
        {
            $this->userTable = 'tmp_' . $randomStr->next() ;
            // $this->userTable = 'tmp_' . randomStr( 24 , 0 ) ;

            $stmt[0]->bindParam( ':users' , $this->userTable , \PDO::PARAM_STR ) ;
            $stmt[0]->execute() ;

        } while ( $stmt[0]->rowCount() !== 0 )  ;

        /*
         *  hvis tabellen 'users' ikke eksisterer,
         *  så
         *      1) opret den
         *      2) initier den med data fra usersCrypt 
         *      3) modificer den m/ primary key  
         */         

        /* dette her fungerer ikke fordi connectpdo er static */
        //  $setupAdm = new Setup( "mysql" , "Adm" ) ;
        //  $dbhAdm   = $setupAdm->connect::getConn() ;

        $sql[1]  = 'create table ' . $this->userTable . ' ' ;
        $sql[1] .= '( ' ;
        $sql[1] .= '    user_id     int , ' ;
        $sql[1] .= '    name        varchar(255) not null , ' ;
        $sql[1] .= '    surname     varchar(255) default \'\' , ' ;
        $sql[1] .= '    phone       varchar(255) not null , ' ;
        $sql[1] .= '    username    varchar(255) not null , ' ;
        $sql[1] .= '        constraint unique (username) , '  ;
        $sql[1] .= '    passwd      varchar(255) not null , ' ;
        $sql[1] .= '    email       varchar(255) not null , ' ;
        $sql[1] .= '        constraint unique (email) ' ;
        $sql[1] .= ') engine = memory ' ;

        //  $stmt[1] = $dbhAdm->prepare( $sql[1] ) ;
        $stmt[1] = $this->dbh->prepare( $sql[1] ) ;
        $stmt[1]->execute() ;

        //  $dbhAdm = null ;

        $sql[3]  = 'insert into ' . $this->userTable . ' ' ;
        $sql[3] .= '        (  user_id ' ;
        $sql[3] .= '        ,  name ,  surname ,  phone ' ;
        $sql[3] .= '        ,  username ,  passwd   ,  email  )' ;
        $sql[3] .= '    values' ;
        $sql[3] .= '        ( :user_id ' ;
        $sql[3] .= '        , :name , :surname , :phone ' ;
        $sql[3] .= '        , :username , :passwd   , :email  )' ;

        $stmt[3] = $this->dbh->prepare( $sql[3] ) ;

        $userCryptDao = new UserCryptDao( $this::$connect ) ;
        $userIds = $userCryptDao->readAll() ;
        while ( $userCryptId = $userIds->fetch( \PDO::FETCH_ASSOC ) )
        {
            $userCrypt = new UserCrypt( $this::$connect , (int) $userCryptId['user_id'] ) ;
            $data = $userCrypt->dataDecrypt() ;
            $data['user_id'] = (int) $userCryptId['user_id'] ;

            $stmt[3]->bindParam( ':user_id'     , $data['user_id']  , \PDO::PARAM_INT ) ;
            $stmt[3]->bindParam( ':name'        , $data['name']     , \PDO::PARAM_STR ) ;
            $stmt[3]->bindParam( ':surname'     , $data['surname']  , \PDO::PARAM_STR ) ;
            $stmt[3]->bindParam( ':phone'       , $data['phone']    , \PDO::PARAM_STR ) ;
            $stmt[3]->bindParam( ':username'    , $data['username'] , \PDO::PARAM_STR ) ;
            $stmt[3]->bindParam( ':passwd'      , $data['passwd']   , \PDO::PARAM_STR ) ;
            $stmt[3]->bindParam( ':email'       , $data['email']    , \PDO::PARAM_STR ) ;

            $stmt[3]->execute() ;
            if ( $stmt[3]->rowCount() !== 1 )
                throw new \Exception('PDO : rowCount != 1 - kunne ikke skrive til tmp_ filen') ;
        }

        /*  alter table users
         *  set 'auto_increment' & 'primary key' on user_id
         *  this has to be done here after the userCrypt DB has been read in 
         *  otherwise, the user_id's from UserCrypt will be overwritten 
         */
        $sql[2]  = 'alter table ' . $this->userTable . ' ' ;
        $sql[2] .= 'change column user_id user_id int auto_increment primary key first ' ;

        $stmt[2] = $this->dbh->prepare( $sql[2] ) ;
        $stmt[2]->execute() ;

        // echo str_repeat( '-' , 50 ) . \PHP_EOL . "created tpm table : {$this->userTable}" . PHP_EOL ;

        foreach ( $stmt as $key => $value )
            $stmt[$key] = null ;
            unset( $key , $value ) ;

    }

    /*
     */
    public function create( Array $user )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $user ) ;

        $sql  = 'insert into ' . $this->userTable . '' ;
        $sql .= '        ( name ,  surname ,  phone ' ;
        $sql .= '        ,  username ,  passwd   ,  email  )' ;
        $sql .= '    values' ;
        $sql .= '        ( :name , :surname , :phone ' ;
        $sql .= '        , :username , :passwd   , :email  )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':name'        , $user['name']     , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':surname'     , $user['surname']  , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':phone'       , $user['phone']    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':username'    , $user['username'] , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':passwd'      , $user['passwd']   , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':email'       , $user['email']    , \PDO::PARAM_STR ) ;

        try { $stmt->execute() ; }
        catch ( \Exception $e )
        {
            // echo 'Caught exception : ' . $e->getMessage() . \PHP_EOL ;
        }
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1 - kunne ikke skrive til en ny bruger i tmp_ tabellen' ) ;

        $user['user_id'] = $this->dbh->lastInsertId() ;
        $userCrypt = new UserCrypt( $this::$connect , $user ) ;

        $stmt = null ;
    return $user['user_id'] ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testUser = new User(  $setup::$connect ) ;
     *      $testUser->read() ;
     *      $testUser->read( 1 ) ;
     *      $testUser->read( 'alias' , 'Slettet' ) ;
     *      $testUser->read( ['navn_for'] , ['Anonymous'] ) ;
     *      $testUser->read( ['navn_for','alias'] , ['Bjarne','BjarneDMat'] ) ;
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
                $sql  = 'select * from ' . $this->userTable . ' ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                try { $stmt->execute() ; }
                catch ( \Exception $e )
                {
                    // echo 'Caught exception : ' . $e->getMessage() . \PHP_EOL ;
                }
 
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from ' . $this->userTable . ' ' ;
                        $sql .= 'where user_id = :user_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':user_id' , $args[0] , \PDO::PARAM_INT ) ;

                        try { $stmt->execute() ; }
                        catch ( \Exception $e )
                        {
                            // echo 'Caught exception : ' . $e->getMessage() . \PHP_EOL ;
                        }
                        break ;
                    default: throw new \Exception('#argumnents error') ;
                }

                break ;
            case 2 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'string' :
                        $sql  = 'select * from ' . $this->userTable . ' ' ;
                        $sql .= "where {$args[0]} = :{$args[0]}"  ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ":{$args[0]}" , $args[1] , \PDO::PARAM_STR ) ;

                        try { $stmt->execute() ; }
                        catch ( \Exception $e )
                        {
                            // echo 'Caught exception : ' . $e->getMessage() . \PHP_EOL ;
                        }

                        break ;
                    case 'array' :
                        $sql  = 'select * from ' . $this->userTable . ' ' ;
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
                        try { $stmt->execute( $args[1] ) ; }
                        catch ( \Exception $e )
                        {
                            // echo 'Caught exception : ' . $e->getMessage() . \PHP_EOL ;
                        }

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
            $values['user_id'] = (int) $values['user_id'] ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1 - kunne ikke læse brugeren') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $userIDs = [] ;
        $stmt = $this->readData( ...$args ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'user_id' )
            {
                $userIDs = [] ;
                while ( $user_id = $stmt->fetchColumn( $i ) )
                {
                    $userIDs[] = (int) $user_id ;
                }
            break ; }
        }
    return $userIDs ; }

    public function update( int $user_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $user_id , $key , $value ] ) ;

        $sql  = 'update ' . $this->userTable . ' ' ;
        $sql .= "set $key = :value " ;
        $sql .= 'where user_id = :user_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $stmt->bindParam( ':user_id' , $user_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':value'   , $value   , \PDO::PARAM_STR ) ;

        try { $stmt->execute() ; }
        catch ( \Exception $e )
        {
            // echo 'Caught exception : ' . $e->getMessage() . \PHP_EOL ;
        }
        $rowCount = $stmt->rowCount();
//         if ( $rowCount !== 1 )
//             throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

        $userCrypt = new UserCrypt( $this::$connect , $user_id ) ;
        $userCrypt->update( $key , $value ) ;

    return $rowCount ; }

    public function delete( int $user_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id ] ) ;

        $sql  = 'delete from ' . $this->userTable . ' ' ;

        $sql .= 'where user_id = :user_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':user_id' , $user_id , \PDO::PARAM_INT ) ;
        try { $stmt->execute() ; }
        catch ( \Exception $e )
        {
            // echo 'Caught exception : ' . $e->getMessage() . \PHP_EOL ;
        }
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

        $userCrypt = new UserCrypt( $this::$connect , $id ) ;
        $userCrypt->delete() ;

    return $rowCount ; }

    public function __destruct() 
    { 
        $sql  = 'drop table ' . $this->userTable . ' ' ;
        $stmt = $this->dbh->prepare(  $sql ) ;
        $stmt->execute() ;
        $this->dbh = null ;
        // echo  "dropped tpm table : {$this->userTable}" . PHP_EOL . str_repeat( '-' , 50 ) . \PHP_EOL ;
    }

}

?>
