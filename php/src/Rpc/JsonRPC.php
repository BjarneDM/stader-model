<?php   namespace Stader\Rpc ;

require_once( dirname( __DIR__ , 2 ) . '/classloader.php' ) ;
use \Stader\Model\Tables\Ticket\{Ticket,Tickets} ;
use \Stader\Model\Tables\User\{UserLogin,User} ;
use \Stader\Model\RandomStr ;

class JsonRPC
{
    private $allowedParams = [ 'jsonrpc' , 'id' , 'method' , 'params' ] ;
    private $jsonData = [] ;
    private $user ;

    function __construct( $json )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $json ] ) ;

        $this->jsonData = json_decode( $json , true ) ;
        if ( ! $this->check( $this->jsonData ) ) return ;
        $this->setResponse() ;
    }

    public function getResponse()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

    return json_encode( $this->jsonData , JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ) ; }

    private function check( Array &$toCheck )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( $toCheck ) ;

        foreach ( array_keys( $toCheck ) as $param )
        {
            switch ( $param )
            {
                case 'jsonrpc' :
                    if ( ! in_array( $this->jsonData['jsonrpc'] , [ '2_0' , '2.0' ] ) )
                    {
                        $this->setError(  
                            $this->jsonData['id'] ,
                            [
                                'code'   => -1000 ,
                                'message' => "jsonrpc {$this->jsonData['jsonrpc']} not supported" ,
                                'data'    => ''
                            ] 
                        ) ;
                        return false ;
                    } else { $this->jsonData['jsonrpc'] = '2.0' ; }
                    break;
            }
        }

        foreach ( array_keys( $toCheck ) as $key )
        {
            if ( ! in_array( $key , $this->allowedParams ) )
            {
                $this->setError(  
                    $this->jsonData['id'] ,
                    [
                        'code'   => -1001 ,
                        'message' => "{$key} is invalid" ,
                        'data'    => ''
                    ] 
                ) ;
            return false ; }
        }

        $missingParams = array_diff( $this->allowedParams , array_keys( $toCheck ) ) ;
        foreach ( $missingParams as $missingParam ) 
        {
            $this->setError(  
                $this->jsonData['id'] ,
                [
                    'code'   => -1002 ,
                    'message' => "{$missingParam} is missing" ,
                    'data'    => ''
                ] 
            ) ;
        return false ; }

    return true ; } 

    private function setError( $id , $errorMsg )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $id , $errorMsg ] ) ;

        $this->jsonData = [] ;
        $this->jsonData['jsonrpc'] = '2.0' ;
        $this->jsonData['id']      = $id ;
        $this->jsonData['error']   =
            [
                'code'    => $errorMsg['code'] ,
                'message' => $errorMsg['message'] ,
                'data'    => $errorMsg['data']
            ] ;
    }

    private function setResponse()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        list( $method , $action ) = explode( '/' , $this->jsonData['method'] ) ;
        switch ( $method )
        {
            case 'test' :
                $this->jsonData['result']['method'] = $this->jsonData['method'] ;
                $this->jsonData['result']['params'] = $this->jsonData['params'] ;
                break ;

            case 'login' :
                $this->methodLogin() ;
                break ;

            case 'tickets' :
                $this->methodTickets() ;
                break ;

            default :
                $this->setError(  
                    $this->jsonData['id'] ,
                    [
                        'code'   => -2999 ,
                        'message' => "jsonrpc {$method} not supported" ,
                        'data'    => ''
                    ] 
                ) ;
                break ;
        }
        unset( $this->jsonData['params'] , $this->jsonData['method'] ) ;
    }

    private function methodLogin()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $user = $this->login( $this->jsonData['params']['username'] , $this->jsonData['params']['passwd'] ) ;
        if ( is_null( $user ) )
        {
            $this->setError(  
                $this->jsonData['id'] ,
                [
                    'code'   => -2800 ,
                    'message' => "login fejlede" ,
                    'data'    => ''
                ] 
            ) ;
        return ; }

        /*
         *  Dette her
         *  1)  giver altid en ny session_id v/ login
         *  2)  muliggør at man kan logge flere brugere ind i samme session 
         *      & de får forskellige session_id
         *
         *  Problem :
         *      hvis man logger den samme bruger ind flere gange
         *      vil denne have flere aktive session_id
         *      indtil php selv rydder op i gamle sessions
         */
        session_write_close() ;
        session_id( ( new RandomStr( [ 'length' => 32 , 'ks' => 5 ] ) )->current() ) ;
        session_start() ;
        $_SESSION['username'] = $user->getData()['username'] ;
        $this->jsonData['result']['authstring'] = session_id() ;
        $this->jsonData['result']['user']       = $user->getData() ;

    }

    private function login( $login , $password )
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // print_r( [ $login , $password ] ) ;

        $user = new UserLogin( [ 'username' => $login , 'passwd' => $password ] ) ;
        if ( is_null( $user->getData() ) ) 
            { return null  ; }
        else
            { return $user ; }
    }

    private function checkAuth()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        $checkAuth = true ;

        if ( ! isset( $this->jsonData['params']['authstring'] ) )
        {
            $this->setError(  
                $this->jsonData['id'] ,
                [
                    'code'   => -2850 ,
                    'message' => "not authenticated" ,
                    'data'    => ''
                ] 
            ) ;
        return $checkAuth = false ; }

        session_write_close() ;
        session_id( $this->jsonData['params']['authstring'] ) ;
        session_start() ;
        if ( ! isset( $_SESSION['username'] ) )
        {
            $this->setError(  
                $this->jsonData['id'] ,
                [
                    'code'   => -2860 ,
                    'message' => "not authenticated" ,
                    'data'    => ''
                ] 
            ) ;
        return $checkAuth = false ; }
    
    return $checkAuth ; }

}
?>
