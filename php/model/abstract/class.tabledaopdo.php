<?php namespace stader\model ;

/*

create table if not exists areas
(
    area_id     int auto_increment primary key ,
    name        varchar(255) not null ,
    description text
) ;

 */
require_once( dirname( __file__ , 2 ) . '/interfaces/interface.cruddao.php' ) ;

class TableDaoPdo implements ICrudDao
{
    private        $dbh     = null ;
    private static $connect = null ;
    private        $table   = null ;

    public function __construct ( $connect , $class )
    {   // echo 'class TableDaoPdo implements ICrudDao __construct' . \PHP_EOL ;
        // var_dump( $connect ) ;
        // var_dump( $class ) ;
        // echo 'connection type : ' . $connect->getType()  . \PHP_EOL ;

        $this->dbh = $connect->getConn() ;
        $this::$connect = $connect ;
        $this->table = explode( '\\' , $class ) ;
        $this->table = strtolower( end( $this->table ) ) ;

    }

    private function getPdoParamType ( $valType )
    {
        $dataType = null ;
        switch ( $valType )
        {
            case 'char' :
            case 'varchar' :
            case 'text' :
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
                throw new \Exception( $dataType . ' : unknow PDO dataType') ;
        } 
    return $dataType ; }

    /*
     */
    public function create( $object )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( $object::$allowedKeys ) ;

        $sql  = 'insert into ' . $this->table ;
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
    private function readDataNamed( $object )
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

    private function readDataPosit( $object )
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

    private function readData( $object )
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

    public function readOne( $object )
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

    public function readAll( $object )
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

    /*  Af en eller anden mærkelig grund fungere dette ikke ?!?
     *      named        parametre fungerer for count( $object->getData() < 2
     *  men positionelle parametre fungerer altid
     *  ?!?!?!?!?
     */
    private function updateNamed( $object , Array $diffValues )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;
        // print_r( $diffValues ) ;

        if ( empty( $diffValues ) ) return 0 ;

        $sql  = 'update ' . $this->table . ' ' ;

        $set = [] ;
        foreach ( $diffValues as $key => $value )
        {
            $set[] = "{$key} = :{$key}" ;
        }   unset( $key , $value ) ; 
        $sql .= 'set ' . implode( ' , ' , $set ) . ' ' ;

        $sql .= 'where id = :id ' ;

        $stmt = $this->dbh->prepare( $sql ) ;

        foreach ( $diffValues as $param => $value )
        {
            $stmt->bindParam( ":{$param}" , $value , $this->getPdoParamType( $object::$allowedKeys[$param] ) ) ;
        }   unset( $param , $value ) ;
        $stmt->bindParam( ':id' , $object->getData()['id'] , \PDO::PARAM_INT ) ;

        $stmt->execute() ;
        $rowCount = $stmt->rowCount();
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    private function updatePosit( $object , Array $diffValues )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $object ) ;

        if ( empty( $diffValues ) ) return 0 ;

        $sql  = 'update ' . $this->table . ' ' ;

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
        if ( $rowCount !== 1 )
            throw new \Exception('PDO : rowCount != 1') ;
        $stmt = null ;

    return $rowCount ; }

    public function update(  $object , Array $diffValues )
    {
        switch ( count( $object->getData() ) )
        {
            case 0  :
            case 1  :
                return $this->updateNamed( $object , $diffValues ) ;
                break ;
            default :
                return $this->updatePosit( $object , $diffValues ) ;
                break ;
        }
    }

    public function delete( $object )
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

    public function __destruct() 
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        $this->dbh = null ;
    }

}

?>
