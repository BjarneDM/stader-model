    private function methodTickets()
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;

        /*
         *  Returnerer alle tickets
         *  m/ auth user's tickets listet 1st
         *  Unassigned tickets står pt sidst i listen
         */

        if ( ! $this->checkAuth() ) return ;

        $this->jsonData['result']['authstring'] = session_id() ;
        $this->user = new User( 'username' , $_SESSION['username'] ) ;
        $this->jsonData['result']['user'] =
        [
            'username' => $this->user->getData()['username'] ,
            'user_id'  => $this->user->getData()['user_id']
        ] ;

        $tickets = new Tickets() ;
        $jsonTickets = [] ;
        foreach ( $tickets as $ticket )
        {
            $jsonTickets[] = $ticket->getData() ;
        }
        usort
        ( 
            $jsonTickets ,
            function ( $a , $b )
            {
                $aUserID = $a['assigned_user_id'] ;
                $bUserID = $b['assigned_user_id'] ;
                if ( $aUserID == $this->user->getData()['user_id'] ) return -1 ;
                if ( $aUserID == $bUserID ) return  0 ;
                return 1 ;
            }
        ) ;
        $this->jsonData['result']['tickets'] = $jsonTickets ;                

    }
