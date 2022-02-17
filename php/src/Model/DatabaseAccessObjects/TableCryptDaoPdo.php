<?php namespace Stader\Model\DatabaseAccessObjects ;

/*

create table if not exists usercrypt
(
    id              int auto_increment primary key ,
    reference_id    int ,
        index(reference _id) ,
    salt            varchar(255) ,
    algo            varchar(255) ,
    tag             varchar(255) ,
    data            text
) ;

 */

use \Stader\Model\Interfaces\ICrudDao ;

class TableCryptDaoPdo implements ICrudDao
{
    private $dbh    = null ;
    private $table  = null ;
    private $iniSettings ; 

    private static $allowedKeys =
        [   'reference_id' => 'int' ,
            'salt'         => 'varchar' ,
            'algo'         => 'varchar' ,
            'tag'          => 'varchar' ,
            'data'         => 'text'
        ] ;

    public function __construct ( $connect , $class )
    {   // echo 'class TableDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // var_dump( $class ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        $this->dbh   = $connect->getConn() ;
        $this->table = explode( '\\' , $class ) ;
        $this->table = strtolower( end( $this->table ) ) . 'crypt' ;

        $this->iniSettings = parse_ini_file(  dirname( __DIR__ , 3 ) . '/settings/connect.ini' , true ) ;
    }

    private function getPdoParamType ( $valType )
    {
        $dataType = null ;
        switch ( $valType )
        {
            case 'varchar' :
            case 'text' :
                $dataType = \PDO::PARAM_STR ;
                break ;
            case 'int' :
            case 'integer' :
                $dataType = \PDO::PARAM_INT ;
                break ;
            default :
                throw new \Exception( $valType . ' : unknown / unsupported PDO dataType for encrypted data') ;
        } 
    return $dataType ; }

    /*
     */
    public function create( $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( self::$allowedKeys ) ;

        $sql  = 'insert into ' . $this->table ;
        $sql .= '        ( '  . implode( ' , '  , array_keys( self::$allowedKeys ) ) . ' )' ;
        $sql .= '    values' ;
        $sql .= '        ( :' . implode( ' , :' , array_keys( self::$allowedKeys ) ) . ' )' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $cryptData = $this->dataEncrypt( $object->getData() ) ;
        $cryptData['reference_id'] = $object->getData()['reference_id'] ;

        foreach ( self::$allowedKeys as $param => $valType )
        {
            $stmt->bindParam( ":{$param}" , $cryptData[$param] , $this->getPdoParamType( $valType ) ) ;
        }

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $stmt = null ;
    return (int) $this->dbh->lastInsertId() ; }

    private function dataEncrypt ( Array $data ) : array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $user ) ;

        unset( $data['id'] , $data['reference_id'] ) ;
        $cipher = $this->iniSettings['crypt']['method'] ;
        if ( in_array( $cipher , openssl_get_cipher_methods() ) )
        {
            $dataJson = json_encode( $data , JSON_NUMERIC_CHECK ) ;
            $key   = openssl_digest( $this->iniSettings['crypt']['key'] , 'sha256' , true ) ;
            $ivlen = openssl_cipher_iv_length( $cipher );
            $iv    = openssl_random_pseudo_bytes( $ivlen );
            $data = openssl_encrypt( $dataJson , $cipher , $key , 0 , $iv , $tag ) ;
        } else { throw new \Exception( '!!!' . $cipher . ' isn\'t a valid encryption method !!!' ) ; }

        return [ 
            'salt' => base64_encode( $iv ) ,
            'algo' => $this->iniSettings['crypt']['method'] , 
            'tag'  => base64_encode( $tag ) , 
            'data' => base64_encode( $data ) ] ; }

    public function dataDecrypt ( Array $data ) : array
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

/*  !!! START !!!
 *  read funktioner
 */
    /*  læser en ny user ind baseret på en select statement
     *  brug :
     *      $testArea->read( 1 ) ;
     *      $testArea->read( 'alias' , 'Slettet' ) ;
     *      $testArea->read( ['navn_for'] , ['Anonymous'] ) ;
     *      $testArea->read( ['navn_for','alias'] , ['Bjarne','BjarneDMat'] ) ;
     */
    private function readDataNamed( $object ) : \PDOStatement
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $sql  = 'select * from ' . $this->table . ' ' ;
        $stmt = null ;

        switch ( count( $object->getData() ) )
        {
            case 0 :
                $stmt = $this->dbh->prepare( $sql ) ;
                break ;
            default :
                if ( isset( $object->getData()['id'] ) )
                {
                    $sql .= 'where id = :id ' ;
                    $stmt = $this->dbh->prepare( $sql ) ;
                    $stmt->bindParam( ':id' , $object->getData()['id'] , \pdo::PARAM_INT ) ;
                } else {
                    $where = [] ;
                    foreach ( $object->getData() as $param => $value )
                    {
                        $where[] = "{$param} = :{$param}" ;
                    }   unset( $param , $value ) ;
                    $sql .= 'where ' . implode( ' and ' , $where ) ;
                
                    $stmt = $this->dbh->prepare( $sql ) ;

                    foreach ( $object->getData() as $param => $value )
                    {
                        $stmt->bindParam( ":{$param}" , $value , $this->getPdoParamType( $object::$allowedKeys[$param] ) ) ;
                    }
                }
                break ;
        }

        $stmt->execute() ;

    return $stmt ; }

    private function readDataPosit( $object ) :\PDOStatement
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $sql  = 'select * from ' . $this->table . ' ' ;
        $stmt = null ;

        switch ( count( $object->getData() ) )
        {
            case 0 :
                $stmt = $this->dbh->prepare( $sql ) ;
                break ;
            default :
                if ( isset( $object->getData()['id'] ) )
                {
                    $sql .= 'where id = ?' ;
                    $stmt = $this->dbh->prepare( $sql ) ;
                } else {
                    $where = [] ;
                    foreach ( $object->getData() as $param => $value )
                    {
                        $where[] = "{$param} = ?" ;
                    }   unset( $param , $value ) ;
                    $sql .= 'where ' . implode( ' and ' , $where ) ;
                
                    $stmt = $this->dbh->prepare( $sql ) ;

                }
                break ;
        }

        $stmt->execute( $object->getValues() ) ;

    return $stmt ; }

    private function readData( $object ) : \PDOStatement
    {
        switch ( count( $object->getData() ) )
        {
            case 0  :
            case 1  :
                return $this->readDataNamed( $object ) ;
                break ;
            default :
                return $this->readDataPosit( $object ) ;
                break ;
        }
    }

    public function readOne( $object ) : array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $stmt = $this->readData( $object ) ;
        if ( $stmt->rowCount() === 1 )
        {
            $values = $stmt->fetch( \PDO::FETCH_ASSOC ) ;
            $values = array_merge( 
                [ 'id' => $values['id'] , 'reference_id' => $values['reference_id'] ] , 
                $this->dataDecrypt( $values ) 
            ) ;
            return $values ;
        } else { 
            throw new \Exception('PDO : rowCount != 1') ; }
    }

    public function readAll( $object ) : array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $ids = [] ;
        $stmt = $this->readData( $object ) ;
        for ( $i=0 ; $i<$stmt->rowCount() ; ++$i )
        {
            $columnMeta = $stmt->getColumnMeta( $i ) ;
            if ( $columnMeta['name'] === 'id' )
            {
                while ( $id = $stmt->fetchColumn( $i ) )
                {
                    $ids[] = (int) $id ;
                }
            break ; }
        }
    return $ids ; }
/*
 *  read funktioner
 *  !!! SLUT !!! */

/*  !!! START !!!
 *  update funktioner
 */
    /*  Af en eller anden mærkelig grund fungere dette ikke ?!?
     *      named        parametre fungerer for count( $object->getData() < 2
     *  men positionelle parametre fungerer altid
     *  ?!?!?!?!?
     */
    private function updateNamed( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( $diffValues ) ;

        if ( empty( $diffValues ) ) return 0 ;

        $sql  = 'update ' . $this->table . ' ' ;

        $set = [] ;
        foreach ( self::$allowedKeys as $key => $value )
        {
            $set[] = "{$key} = :{$key}" ;
        }   unset( $key , $value ) ; 
        $sql .= 'set ' . implode( ' , ' , $set ) . ' ' ;

        $sql .= 'where id = :id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $cryptData = $this->dataEncrypt( $object->getData() ) ;
        foreach ( $cryptData as $param => $value )
        {
            $stmt->bindParam( ":{$param}" , $value , $this->getPdoParamType( self::$allowedKeys[$param] ) ) ;
        }   unset( $param , $value ) ;
        $stmt->bindParam( ':reference_id' , $object->getData()['reference_id'] , \PDO::PARAM_INT ) ;
        $stmt->bindParam( ':id'           , $object->getData()['id']           , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        $stmt = null ;

    return $rowCount ; }

    private function updatePosit( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $sql  = 'update ' . $this->table . ' ' ;

        $cryptData = $this->dataEncrypt( $object->getData() ) ;
        $cryptData['reference_id'] = $object->getData()['reference_id'] ;
        $set = [] ;
        foreach ( $cryptData as $key => $value )
        {
            $set[] = "{$key} = ?" ;
        }   unset( $key , $value ) ; 
        $sql .= 'set ' . implode( ' , ' , $set ) . ' ' ;

        $sql .= 'where id = ? ' ;
        $stmt = $this->dbh->prepare( $sql ) ;

        $cryptData['id'] = $object->getData()['id'] ;

        $stmt->execute( array_values( $cryptData ) ) ;
        $rowCount = $stmt->rowCount();
        $stmt = null ;

    return $rowCount ; }

    public function update( $object , $dummy = [] ) : int
    {
        switch ( count( $object->getData() ) )
        {
            case 1  :
                $rowCount = $this->updateNamed( $object ) ;
                break ;
            default :
                $rowCount = $this->updatePosit( $object ) ;
                break ;
        }
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
    return $rowCount ; }
/*
 *  update funktioner
 *  !!! SLUT !!! */

    public function delete( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id ] ) ;

        $sql  = 'delete from ' . $this->table . ' ' ;
        $sql .= 'where id = :id' ;

        $stmt = $this->dbh->prepare(  $sql ) ;

        $stmt->bindParam( ':id' , $object->getData()['id'] , \PDO::PARAM_INT ) ;
 
        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

/*  !!! START !!!
 *  functions for \Iterator

            OK - det viser sig, at for MySQL bliver $cursorOrientation & $cursorOffset i
                    https://www.php.net/manual/en/pdostatement.fetch.php
            !totalt! ignoreret [thumbs-down]
            Endvidere bliver array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL) i $dbh->prepare($sql , ... )
            !også! ignoreret. Der er mao ingen som helt support for cursor.
            Den oprindelige logik var sund nok - bare en skam at MySQL overhovedet ikke understøtter den

 */
    private $stmt   = null ;
    private $row    = null ;

    public function rewind( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $this->stmt = $this->readData( $object ) ;
        $this->row  = $this->stmt->fetch( \PDO::FETCH_ASSOC ) ;
    }

    public function count( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt ) ) $this->rewind( $object ) ;
        return $this->stmt->rowCount() ;
    }

    public function next( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt ) ) $this->rewind( $object ) ;
        $this->row = $this->stmt->fetch( \PDO::FETCH_ASSOC ) ;
    }

    public function valid( $object )  : bool
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt ) ) $this->rewind( $object ) ;
        if ( $this->row === false )
             { return false ; } 
        else { return true ; }
    }

    public function current( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt ) ) $this->rewind( $object ) ;
        return (int) $this->row['id'] ;
    }

    public function key( $object ) : int | false
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt ) ) $this->rewind( $object ) ;
        if ( $this->row === false )
             { return false ; } 
        else { return (int) $this->row['id'] ; }
    }
/*
 *  funktioner for \Iterator
 *  !!! SLUT !!! */

    public function deleteAllOld( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $sql  = 'delete from ' . $this->table . ' ' ;
        $sql .= 'where id = :id' ;
        $stmtHere = $this->dbh->prepare( $sql ) ;

        $this->stmt = $this->readData( $object ) ;
        while ( $rowHere = $this->stmt->fetch( \PDO::FETCH_ASSOC ) )
        {
            $stmtHere->bindParam( ':id' , $rowHere['id'] , \PDO::PARAM_INT ) ;
            $stmtHere->execute() ;
        }   unset( $rowHere ) ;
    }

    public function deleteAll() : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $sql  = 'delete from ' . $this->table . ' ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->execute() ;
    }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->dbh = null ;
    }

}

?>