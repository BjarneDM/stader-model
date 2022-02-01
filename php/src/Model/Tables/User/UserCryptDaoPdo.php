<?php namespace Stader\Model ;

/*

create table userscrypt
(
    user_id     int primary key ,
    salt        varchar(255) ,
    algo        varchar(255) ,
    tag         varchar(255) ,
    data        text
)

 */

require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class UserCryptDaoPdo implements ICrudDao
{

    private $dbh = null ;
    private $iniSettings ; 
    protected $values = [] ;

    function __construct ( $connect )
    {   // echo 'class UserCryptDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;
        $this->iniSettings = parse_ini_file(  dirname( __file__ , 3 ) . '/settings/connect.ini' , true ) ;
    }

    private function dataEncrypt ( Array $user )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $user ) ;

        unset( $user['user_id'] ) ;
        $cipher = $this->iniSettings['crypt']['method'] ;
        if ( in_array( $cipher , openssl_get_cipher_methods() ) )
        {
            $dataJson = json_encode( $user , JSON_NUMERIC_CHECK ) ;
            $key   = openssl_digest( $this->iniSettings['crypt']['key'] , 'sha256' , true ) ;
            $ivlen = openssl_cipher_iv_length( $cipher );
            $iv    = openssl_random_pseudo_bytes( $ivlen );
            $data = openssl_encrypt( $dataJson , $cipher , $key , 0 , $iv , $tag ) ;
        } else { throw new \Exception( '!!!' . $cipher . ' isn\'t a valid encryption method !!!' ) ; }
    return [ base64_encode( $iv ) ,
             $this->iniSettings['crypt']['method'] , 
             base64_encode( $tag ) , 
             base64_encode( $data ) ] ; }

    public function dataDecrypt ( Array $data )
    {
        $cipher   = $data['algo'] ;
        $key      = openssl_digest( $this->iniSettings['crypt']['key'] , 'sha256' , true ) ;
        $dataJson = openssl_decrypt( 
                        base64_decode( $data['data'] ) , 
                        $cipher , $key , 0 , 
                        base64_decode( $data['salt'] ) , 
                        base64_decode( $data['tag'] ) ) ;
        $data     = json_decode( $dataJson , true ) ;
    return $data ; }

    /*
     */
    public function create( Array $user )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $user ) ;

        $values = [] ;
        $values['user_id'] = $user['user_id'] ;

        $sql  = 'insert into userscrypt ' ;
        $sql .= '        (  user_id ,  salt ,  algo ,  tag ,  data ) ' ;
        $sql .= '    values ' ;
        $sql .= '        ( :user_id , :salt , :algo , :tag , :data ) ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        list( $salt , $algo , $tag , $data ) = $this->dataEncrypt( $user ) ;

        $values['salt'] = $salt ;
        $values['algo'] = $algo ;
        $values['tag']  = $tag  ;
        $values['data'] = $data ;

        $stmt->bindParam( ':user_id' , $user['user_id'] , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':salt'    , $salt            , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':algo'    , $algo            , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':tag'     , $tag             , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':data'    , $data            , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $values ; }

    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testUser = new User(  $setup::$connect ) ;
     *      $testUser->read() ;
     *      $testUser->read( 1 ) ;
     */
    private function readData( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $sql  = '' ;
        $stmt = null ;

        switch ( count( $args ) )
        {
            case 0 :
                $sql  = 'select user_id from userscrypt ' ;
                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;
                break ;
            case 1 :
                // echo count($args ) . " : " . gettype( $args[0] ) . \PHP_EOL ;
                switch ( gettype( $args[0] ) )
                {
                    case 'integer' :
                        $sql  = 'select * from userscrypt ' ;
                        $sql .= 'where user_id = :user_id ' ;

                        $stmt = $this->dbh->prepare( $sql ) ;

                        $stmt->bindParam( ':user_id' , $args[0] , \PDO::PARAM_INT ) ;

                        $stmt->execute() ;
                        break ;
                    default: throw new \Exception( gettype( $args[0] ) . ' : PDO readData #argumnents error' ) ;
                }
                break ;
            default: throw new \Exception( count( $args ) . ' : PDO readData #argumnents error' ) ;
        }

    return $stmt ; }

    public function readOne( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        $stmt = $this->readData( ...$args ) ;
        if ( $stmt->rowCount() === 1 )
        {
            return $stmt->fetch( \PDO::FETCH_ASSOC ) ;
        } else { throw new \Exception('PDO readOne UserCrypt: rowCount != 1') ; }
    }

    public function readAll( ...$args )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $args ) ;

        return self::readData( ...$args ) ;
    }

    public function update( int $user_id , string $key , $value )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $user_id , $key , $value ] ) ;

        $sql  = 'select * from userscrypt ' ;
        $sql .= 'where user_id = :user_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->bindParam( ':user_id' , $user_id , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO update UserCrypt : rowCount != 1') ;

        $userCryptData = $stmt->fetch( \PDO::FETCH_ASSOC ) ;

        $dataValues = $this->dataDecrypt( $userCryptData ) ;
        $dataValues[$key] = $value ;
        list( $salt , $algo , $tag , $data ) = $this->dataEncrypt( $dataValues ) ;

        $sql  = 'update userscrypt ' ;
        $sql .= 'set ' ;
        $sql .= '   salt = :salt , ' ;
        $sql .= '   algo = :algo , ' ;
        $sql .= '   tag  = :tag  , ' ;
        $sql .= '   data = :data   ' ;
        $sql .= 'where user_id = :user_id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $userCryptData['salt'] = $salt ;
        $userCryptData['algo'] = $algo ;
        $userCryptData['tag']  = $tag  ;
        $userCryptData['data'] = $data ;

        $stmt->bindParam( ':user_id' , $user_id , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':salt'    , $salt    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':algo'    , $algo    , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':tag'     , $tag     , \PDO::PARAM_STR ) ;
        $stmt->bindParam( ':data'    , $data    , \PDO::PARAM_STR ) ;

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return [ $rowCount , $userCryptData ] ; }

    public function delete( int $user_id )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $sql  = 'delete from userscrypt ' ;
        $sql .= 'where user_id = :user_id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':user_id' , $user_id , \PDO::PARAM_INT ) ;
        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;
    return $rowCount ; }

    public function __destruct() 
    { 
        $this->dbh = null ;
    }

}

?>
