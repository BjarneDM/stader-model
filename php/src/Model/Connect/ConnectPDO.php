<?php namespace Stader\Model\Connect ;

use \Stader\Model\Interfaces\DbDriver ;
use \Stader\Model\Traits\Settings ;

class ConnectPDO extends DbDriver
{
    private $conn = null ;
    private $type = null ;
 
    /**
    * This method returns the connection object.
    * If it has not been yet created, this method
    * instantiates it based on 
    * variables defined in connect.ini
    * @return PDO the connection object
    */
    function __construct( $dbType )
    {   // echo 'class ConnectPDO extends DbDriver __construct' . \PHP_EOL ;
        // echo $dbType  . \PHP_EOL ;
        // print_r( ['before',$this->conn,$this->type] ) ;

        if ( ! self::$iniSettings ) $this->getSettings() ;

        switch ( self::$iniSettings[$dbType]['method'] )
        {
            case 'mysql'  :
                $connStr  = self::$iniSettings[$dbType]['pdo'] ;
                $connStr .= ':host=' . self::$iniSettings[$dbType]['host'] ;
                if ( self::$iniSettings[$dbType]['host'] !== 'localhost')
                    $connStr .= ';port=' . self::$iniSettings[$dbType]['port'] ;
                $connStr .= ';dbname=' . self::$iniSettings[$dbType]['dbname'] ;
                $connStr .= ';charset=utf8mb4' ;
 
                break ;
 
            case 'pgsql'  :
            case 'sqlite' :
                new \Exception( "PDOdriver " . $dbType . " er ikke implementeret" ) ;
                break ;
        }

        /*
        print_r( 
        [
            $connStr , 
            $iniSettings[$type]['user'] , 
            $iniSettings[$type]['pass'] 
        ] ) ;
        */

        try
        {
            $this->conn = new \PDO( $connStr , self::$iniSettings[$dbType]['user'] , self::$iniSettings[$dbType]['pass'] ) ;
            $this->conn->setAttribute
            (
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
            ) ;
        }
        catch( \PDOException $e )
        {   echo $e->getMessage() . \PHP_EOL ;
            // showHeader('Error') ;
            // showError("Sorry, an error has occurred. Please
            // try your request later\n" . $e->getMessage()) ;
        }
    }

    use Settings ;

    public function getConn() { return $this->conn ; }
    
    public function getType() { return $this->type ; }

    function __destruct() { $this->conn = null ; }
}

?>
