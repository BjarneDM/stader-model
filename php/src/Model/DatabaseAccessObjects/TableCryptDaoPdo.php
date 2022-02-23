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
use \Stader\Model\Connect\DatabaseSetup ;

class TableCryptDaoPdo 
    extends DatabaseSetup
    implements ICrudDao
{
    private static $allowedKeys =
        [   'reference_id' => 'int' ,
            'salt'         => 'varchar' ,
            'algo'         => 'varchar' ,
            'tag'          => 'varchar' ,
            'data'         => 'text'
        ] ;

    public function __construct ( $dbType , $thisClass , $allowedKeys )
    {   // echo 'class TableDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $database ) ;
        // var_dump( $thisClass ) ;

        parent::__construct( $dbType) ;
    }

    private function getTable ( $thisClass )
    {
        $table = explode( '\\' , $thisClass ) ;
        $table = strtolower( end( $table ) ) . 'crypt' ;
    return $table ; }

    private function getDatabase ( $object )
    {
        return self::$iniSettings[ $object::$dbType ]['dbname'] ;
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

    public function create( $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( self::$allowedKeys ) ;

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'insert into ' . $this->getTable( $object::$thisClass ) ;
        $sql .= '        ( '  . implode( ' , '  , array_keys( self::$allowedKeys ) ) . ' )' ;
        $sql .= '    values' ;
        $sql .= '        ( :' . implode( ' , :' , array_keys( self::$allowedKeys ) ) . ' )' ;
        // echo $sql . \PHP_EOL ;

        $stmt = $dbh->prepare( $sql ) ;

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
    return (int) $dbh->lastInsertId() ; }

    private function dataEncrypt ( Array $data ) : array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $user ) ;

        unset( $data['id'] , $data['reference_id'] ) ;
        $cipher = self::$iniSettings['crypt']['method'] ;
        if ( in_array( $cipher , openssl_get_cipher_methods() ) )
        {
            $dataJson = json_encode( $data , JSON_NUMERIC_CHECK ) ;
            $key   = openssl_digest( self::$iniSettings['crypt']['key'] , 'sha256' , true ) ;
            $ivlen = openssl_cipher_iv_length( $cipher );
            $iv    = openssl_random_pseudo_bytes( $ivlen );
            $data = openssl_encrypt( $dataJson , $cipher , $key , 0 , $iv , $tag ) ;
        } else { throw new \Exception( '!!!' . $cipher . ' isn\'t a valid encryption method !!!' ) ; }

        return [ 
            'salt' => base64_encode( $iv ) ,
            'algo' => self::$iniSettings['crypt']['method'] , 
            'tag'  => base64_encode( $tag ) , 
            'data' => base64_encode( $data ) ] ; }

    public function dataDecrypt ( Array $data ) : array
    {
        $cipher   = $data['algo'] ;
        $key      = openssl_digest( self::$iniSettings['crypt']['key'] , 'sha256' , true ) ;
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

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'select * from ' . $this->getTable( $object::$thisClass ) . ' ' ; 
        $stmt = null ;

        switch ( count( $object->getData() ) )
        {
            case 0 :
                $stmt = $dbh->prepare( $sql ) ;
                break ;
            default :
                if ( isset( $object->getData()['id'] ) )
                {
                    $sql .= 'where id = :id ' ;
                    $stmt = $dbh->prepare( $sql ) ;
                    $stmt->bindParam( ':id' , $object->getData()['id'] , \pdo::PARAM_INT ) ;
                } else {
                    $where = [] ;
                    foreach ( $object->getData() as $param => $value )
                    {
                        $where[] = "{$param} = :{$param}" ;
                    }   unset( $param , $value ) ;
                    $sql .= 'where ' . implode( ' and ' , $where ) ;
                
                    $stmt = $dbh->prepare( $sql ) ;

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

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'select * from ' . $this->getTable( $object::$thisClass ) . ' ' ;
        $stmt = null ;

        switch ( count( $object->getData() ) )
        {
            case 0 :
                $stmt = $dbh->prepare( $sql ) ;
                break ;
            default :
                if ( isset( $object->getData()['id'] ) )
                {
                    $sql .= 'where id = ?' ;
                    $stmt = $dbh->prepare( $sql ) ;
                } else {
                    $where = [] ;
                    foreach ( $object->getData() as $param => $value )
                    {
                        $where[] = "{$param} = ?" ;
                    }   unset( $param , $value ) ;
                    $sql .= 'where ' . implode( ' and ' , $where ) ;
                
                    $stmt = $dbh->prepare( $sql ) ;

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
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        print_r( $object ) ;

        if ( empty( $diffValues ) ) return 0 ;

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'update ' . $this->getTable( $object::$thisClass ) ;

        $set = [] ;
        foreach ( self::$allowedKeys as $key => $value )
        {
            $set[] = "{$key} = :{$key}" ;
        }   unset( $key , $value ) ; 
        $sql .= 'set ' . implode( ' , ' , $set ) . ' ' ;

        $sql .= 'where id = :id ' ;

        $stmt = $dbh->prepare( $sql ) ;

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
    {   echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        print_r( $object ) ;

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'update ' . $this->getTable( $object::$thisClass ) ;

        $cryptData = $this->dataEncrypt( $object->getData() ) ;
        $cryptData['reference_id'] = $object->getData()['reference_id'] ;
        $set = [] ;
        foreach ( $cryptData as $key => $value )
        {
            $set[] = "{$key} = ?" ;
        }   unset( $key , $value ) ; 
        $sql .= 'set ' . implode( ' , ' , $set ) . ' ' ;

        $sql .= 'where id = ? ' ;
        $stmt = $dbh->prepare( $sql ) ;

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

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'delete from ' . $this->getTable( $object::$thisClass ) ;
        $sql .= 'where id = :id' ;

        $stmt = $dbh->prepare(  $sql ) ;

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
    private $stmt = [] ;
    private $row  = [] ;

    public function rewind( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $this->stmt[ $this->getTable( $object::$thisClass ) ] = $this->readData( $object ) ;
        $this->row  = $this->stmt[ $this->getTable( $object::$thisClass ) ]->fetch( \PDO::FETCH_ASSOC ) ;
    }

    public function count( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        return $this->stmt[ $this->getTable( $object::$thisClass ) ]->rowCount() ;
    }

    public function next( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        $this->row = $this->stmt[ $this->getTable( $object::$thisClass ) ]->fetch( \PDO::FETCH_ASSOC ) ;
    }

    public function valid( $object )  : bool
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        if ( $this->row === false )
             { return false ; } 
        else { return true ; }
    }

    public function current( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        return (int) $this->row['id'] ;
    }

    public function key( $object ) : int | false
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
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

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'delete from ' . $this->getTable( $object::$thisClass ) ;
        $sql .= 'where id = :id' ;
        $stmtHere = $dbh->prepare( $sql ) ;

        $this->stmt = $this->readData( $object ) ;
        while ( $rowHere = $this->stmt->fetch( \PDO::FETCH_ASSOC ) )
        {
            $stmtHere->bindParam( ':id' , $rowHere['id'] , \PDO::PARAM_INT ) ;
            $stmtHere->execute() ;
        }   unset( $rowHere ) ;
    }

    public function deleteAll( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $dbh  = self::$connect[ $this->getDatabase( $object ) ]->getConn() ;

        $sql  = 'delete from ' . $this->getTable( $object::$thisClass ) ;
        $stmt = $dbh->prepare( $sql ) ;
        $stmt->execute() ;
    }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $dbh = null ;
    }

}

?>
