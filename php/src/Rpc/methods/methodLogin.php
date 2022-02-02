    private function methodLogin()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        function login( $login , $password )
        {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
            // print_r( [ $login , $password ] ) ;

            $user = new UserLogin( [ 'username' => $login , 'passwd' => $password ] ) ;
            if ( is_null( $user->getData() ) ) 
                { return null  ; }
            else
                { return $user ; }
        }

        $user = login( $this->jsonData['params']['username'] , $this->jsonData['params']['passwd'] ) ;
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
