<?php namespace Stader\Model\DatabaseAccessObjects ;

use \Stader\Model\Interfaces\ICrudDao ;
use \Stader\Model\Connect\DatabaseSetup ;
use \Stader\Model\Settings ;

class TableDaoPdo 
    implements ICrudDao
{

    private DatabaseSetup $databasesetup ;
    private Settings $iniSettings ;
    private $dbh ;

    public function __construct ( $dbType )
    {   // echo 'class TableDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // print_r( ['dbType' => $dbType , 'class' => $thisClass , 'allowedKeys' => $allowedKeys] ) ;

        $this->iniSettings = Settings::getInstance() ;
        $this->databasesetup = DatabaseSetup::getInstance() ;
        $this->dbh = $this->databasesetup->getDBH( $dbType ) ;
    }

    private function getTable ( $thisClass ) : string
    {
        $table = explode( '\\' , $thisClass ) ;
        $table = strtolower( end( $table ) ) ;
    return $table ; }

    private function getDatabase ( $object )
    {
        return self::$iniSettings->getSetting( $object::$dbType, 'dbname' ) ;
    }

    private function getPdoParamType ( $valType )
    {
        $dataType = null ;
        switch ( $valType )
        {
            case 'char' :
            case 'varchar' :
            case 'text' :
            case 'datetime' :
                $dataType = \PDO::PARAM_STR ;
                break ;
            case 'int' :
            case 'integer' :
                $dataType = \PDO::PARAM_INT ;
                break ;
            case 'bool' :
                $dataType = \PDO::PARAM_BOOL ;
                break ;
            case 'null' :
                $dataType = \PDO::PARAM_NULL ;
                break ;
            default :
                throw new \Exception( $valType . ' : unknown PDO dataType') ;
        } 
    return $dataType ; }


/*
select * 
from information_schema.tables 
where table_schema = "" 
and table_name = "{$this->table}"
 */
    /*
     *  opretter logtabeller hvis de ikke findes
     */
    private function createTable ( $dbType , $table , $thisClass ) : void
    {   // print_r( ['dbType' => $dbType , 'table' => $table , 'class' => $class] ) ;
        // print_r( self::$connect ) ;
        // print_r( self::$iniSettings($dbType) ) ;

        $sql  = "select * " ;
        $sql .= "from information_schema.tables " ;
        $sql .= "where table_schema = \"". self::$iniSettings->getSetting($dbType, 'dbname') ."\" " ;
        $sql .= "and table_name = \"{$table}\" " ;

        //  echo $sql  . \PHP_EOL ;

        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->execute() ;

        //  echo "\$stmt->rowCount() : {$stmt->rowCount()}" . \PHP_EOL ;
        switch ( $stmt->rowCount() )
        {
            // burde kun kunne give [0,1]
            case 0 :
                $allowedKeys = array_keys( $thisClass::$allowedKeys )  ;
                // print_r( $allowedKeys ) ; exit ;

                $sql  = "create table if not exists {$table} " ;
                $sql .= "( " ;
                $sql .= "    id                  int auto_increment primary key , " ;
                $sql .= "    {$allowedKeys[0]}   int , " ;
                $sql .= "        index ({$allowedKeys[0]}) , " ;
                $sql .= "    {$allowedKeys[1]}   varchar(255) , " ;
                $sql .= "        index ({$allowedKeys[1]}) , " ;
                $sql .= "    old_value           text default null , " ;
                $sql .= "    new_value           text default null , " ;
                $sql .= "    log_timestamp       datetime " ;
                $sql .= "        default current_timestamp , " ;
                $sql .= "        index (log_timestamp) " ;
                $sql .= ") " ;

                // echo $sql  . \PHP_EOL ;

                $stmt = $this->dbh->prepare( $sql ) ;
                $stmt->execute() ;

                break ;
            case 1 :
                break ;
            default :
                break ;
        }
    }

    public function create( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( $object::$allowedKeys ) ;

        $this->createTable( 
            $object::$dbType , 
            $this->getTable( $object::$thisClass ) , 
            $object::$thisClass ) ;


        $sql  = 'insert into ' . $this->getTable( $object::$thisClass ) ;
        $sql .= '        ( '  . implode( ' , '  , array_keys( $object::$allowedKeys ) ) . ' )' ;
        $sql .= '    values' ;
        $sql .= '        ( :' . implode( ' , :' , array_keys( $object::$allowedKeys ) ) . ' )' ;

        // echo $sql . \PHP_EOL ;
        $stmt = $this->dbh->prepare( $sql ) ;

        foreach ( $object::$allowedKeys as $param => $valType )
        {
            $stmt->bindParam( ":{$param}" , $object->getData()[$param] , $this->getPdoParamType( $valType ) ) ;
        }

        $stmt->execute() ;
        if ( $stmt->rowCount() !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;

        $stmt = null ;
    return (int) $this->dbh->lastInsertId() ; }

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
        // print_r( $object::$allowedKeys ) ;

        $sql  = 'select * from ' . $this->getTable( $object::$thisClass ) . ' ' ;
        $stmt = null ;

        $setOrderBy = function () use ( $object ) : string
        {
            $orderBy = '' ;
            if ( null !== $object->getOrderBy() )
            $orderBy = ' order by ' . $object->getOrderBy() ;
        return $orderBy ; } ;

        switch ( count( $object->getData() ) )
        {
            case 0 :
                $sql .= $setOrderBy() ;
                $stmt = $this->dbh->prepare( $sql ) ;
                break ;
            default :
                if ( isset( $object->getData()['id'] ) )
                {
                    $sql .= 'where id = :id ' ;
                    $sql .= $setOrderBy() ;

                    $stmt = $this->dbh->prepare( $sql ) ;
                    $stmt->bindParam( ':id' , $object->getData()['id'] , \pdo::PARAM_INT ) ;
                } else {
                    $where = [] ;
                    foreach ( $object->getData() as $param => $value )
                    {
                        $where[] = "{$param} = :{$param}" ;
                    }   unset( $param , $value ) ;
                    $sql .= 'where ' . implode( ' and ' , $where ) ;
                    $sql .= $setOrderBy() ;
                
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

    private function readDataPosit( $object ) : \PDOStatement
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $sql  = 'select * from ' . $this->getTable( $object::$thisClass ) . ' ' ;
        $stmt = null ;

        switch ( count( $object->getData() ) )
        {
            case 0 :
                break ;
            default :
                if ( isset( $object->getData()['id'] ) )
                {
                    $sql .= 'where id = ?' ;
                } else {
                    $where = [] ;
                    foreach ( $object->getData() as $param => $value )
                    {
                        $where[] = "{$param} = ?" ;
                    }   unset( $param , $value ) ;
                    $sql .= 'where ' . implode( ' and ' , $where ) ;
                
                }
                break ;
        }

        if ( null !== $object->getOrderBy() )
        $sql .= 'order by ' . $object->getOrderBy() ;

        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->execute( $object->getValues() ) ;

    return $stmt ; }

    private function readData( $object ) : \PDOStatement
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo count( $object->getData() ). \PHP_EOL ;

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
            $values['id'] = (int) $values['id'] ;
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

    public function readNULL( $object ) : array
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $sql  = "select column_name " ;
        $sql .= "from information_schema.columns " ;
        $sql .= "where table_schema = '" . $this->iniSettings->getSetting($object::$dbType, 'dbname') . "' " ;
        $sql .= "and table_name = '". $this->getTable( $object::$thisClass ) ."' " ;

        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->execute() ;
        foreach ( $stmt->fetchAll( \PDO::FETCH_COLUMN ) as $column )
            $values[ $column ] = NULL ;

    return $values ; }


/*  !!! START !!!
 *  update funktioner
 */
    /*  Af en eller anden mærkelig grund fungere dette ikke ?!?
     *      named        parametre fungerer for count( $object->getData() < 2
     *  men positionelle parametre fungerer altid
     *  ?!?!?!?!?
     */
    private function updateNamed( $object , Array $diffValues ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( $diffValues ) ;

        if ( empty( $diffValues ) ) return 0 ;

        $sql  = 'update ' . $this->getTable( $object::$thisClass ) . ' ' ;

        $set = [] ;
        foreach ( $diffValues as $key => $value )
        {
            $set[] = "{$key} = :{$key}" ;
        }   unset( $key , $value ) ; 
        $sql .= 'set ' . implode( ' , ' , $set ) . ' ' ;

        $sql .= 'where id = :id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $totalKeys = array_merge( $object::$allowedKeys , $object::$privateKeys ) ;
        foreach ( $diffValues as $param => $value )
        {
            $stmt->bindParam( ":{$param}" , $value , $this->getPdoParamType( $totalKeys[$param] ) ) ;
        }   unset( $param , $value ) ;
        $stmt->bindParam( ':id' , $object->getData()['id'] , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        $stmt = null ;

    return $rowCount ; }

    private function updatePosit( $object , Array $diffValues ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( $diffValues ) ;

        if ( empty( $diffValues ) ) return 0 ;

        $sql  = 'update ' . $this->getTable( $object::$thisClass ) . ' ' ;

        $set = [] ;
        foreach ( $diffValues as $key => $value )
        {
            $set[] = "{$key} = ?" ;
        }   unset( $key , $value ) ; 
        $sql .= 'set ' . implode( ' , ' , $set ) . ' ' ;

        $sql .= 'where id = ? ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        $diffValues['id'] = $object->getData()['id'] ;

        $stmt->execute( array_values( $diffValues ) ) ;
        $rowCount = $stmt->rowCount();
        $stmt = null ;

    return $rowCount ; }

    public function update( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // exit ;

        $diffValues = $object->getDiff() ;

        switch ( count( $diffValues ) )
        {
            case 0  :
                return 0 ;
                break ;
            case 1  :
                $rowCount = $this->updateNamed( $object , $diffValues ) ;
                break ;
            default :
                $rowCount = $this->updatePosit( $object , $diffValues ) ;
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

        $sql  = 'delete from ' . $this->getTable( $object::$thisClass ) . ' ' ;
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
    private $stmt = [] ;
    private $row  = [] ;

    public function rewind( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $object::$thisClass . \PHP_EOL ;
        // print_r( $object ) ;

        $this->stmt[ $this->getTable( $object::$thisClass ) ] = $this->readData( $object ) ;
        $this->row [ $this->getTable( $object::$thisClass ) ] 
            = $this->stmt[ $this->getTable( $object::$thisClass ) ]->fetch( \PDO::FETCH_ASSOC ) ;

        // print_r( $this->stmt ) ;
        // print_r( $this->row  ) ;
    }

    public function count( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        return $this->stmt[ $this->getTable( $object::$thisClass ) ]->rowCount() ;
    }

    public function next( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        $this->row[ $this->getTable( $object::$thisClass ) ] = 
            $this->stmt[ $this->getTable( $object::$thisClass ) ]->fetch( \PDO::FETCH_ASSOC ) ;

        // print_r( $this->stmt ) ;
        // print_r( $this->row  ) ;
    }

    public function valid( $object )  : bool
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        if ( $this->row[ $this->getTable( $object::$thisClass ) ] === false )
             { return false ; } 
        else { return true ; }
    }

    public function current( $object ) : int
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        return (int) $this->row[ $this->getTable( $object::$thisClass ) ]['id'] ;
    }

    public function key( $object ) : int | false
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        if ( is_null( $this->stmt[ $this->getTable( $object::$thisClass ) ] ) ) $this->rewind( $object ) ;
        if ( $this->row[ $this->getTable( $object::$thisClass ) ] === false )
             { return false ; } 
        else { return (int) $this->row[ $this->getTable( $object::$thisClass ) ]['id'] ; }
    }
/*
 *  functions for \Iterator
 *  !!! SLUT !!! */

    public function deleteAllOld( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        $sql  = 'delete from ' . $this->getTable( $object::$thisClass ) . ' ' ;
        $sql .= 'where id = :id' ;
        $stmtHere = $this->dbh->prepare( $sql ) ;

        $this->stmt[ $this->getTable( $object::$thisClass ) ] = $this->readData( $object ) ;
        while ( $rowHere = $this->stmt[ $this->getTable( $object::$thisClass ) ]->fetch( \PDO::FETCH_ASSOC ) )
        {
            $stmtHere->bindParam( ':id' , $rowHere['id'] , \PDO::PARAM_INT ) ;
            $stmtHere->execute() ;
        }   unset( $rowHere ) ;
    }

    public function deleteAll( $object ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $sql  = 'delete from ' . $this->getTable( $object::$thisClass ) . ' ' ;
        $stmt = $this->dbh->prepare( $sql ) ;
        $stmt->execute() ;
    }

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
    }

}

?>
