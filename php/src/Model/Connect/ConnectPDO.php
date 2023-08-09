<?php namespace Stader\Model\Connect ;

use \Stader\Model\Interfaces\DbDriver ;
use \Stader\Model\Settings ;

class ConnectPDO extends DbDriver
{
    private $conn   = null ;
    private $dbType = null ;
    private Settings $iniSettings ;
 
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

        $this->dbType = $dbType ;
        $this->iniSettings = Settings::getInstance() ;

        switch ( $this->iniSettings->getSetting($dbType, 'method') )
        {
            case 'mysql'  :
                $connStr  = $this->iniSettings->getSetting($dbType, 'pdo') ;
                $connStr .= ':host=' . $this->iniSettings->getSetting($dbType, 'host') ;
                if ( $this->iniSettings->getSetting($dbType, 'host') !== 'localhost')
                    $connStr .= ';port=' . $this->iniSettings->getSetting($dbType, 'port') ;
                $connStr .= ';dbname=' . $this->iniSettings->getSetting($dbType, 'dbname') ;
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
            $this->iniSettings->getSetting($dbType, 'user') , 
            $this->iniSettings->getSetting($dbType, 'pass')
        ] ) ;
         */

        try
        {
            $this->conn = 
                new \PDO( 
                    $connStr , 
                    $this->iniSettings->getSetting($dbType, 'user') , 
                    $this->iniSettings->getSetting($dbType, 'pass') 
                ) ;
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

    public function getConn() { return $this->conn ; }
    
    public function getType() { return $this->dbType ; }

    function __destruct() { $this->conn = null ; }
}

?>
