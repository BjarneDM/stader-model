<?php 

/*
 *  functions
 */
 
include_once( 'functions/dirList.php' ) ;

function findSessions ( $key , $preg )
/*
 *  return all sessions where $key = /$preg/
 */
{   global $allSessions ;

    $sessions = [] ;
    foreach ( $allSessions as $session => $values )
    {
        if ( preg_match( "/{$preg}/", $values[$key]['value'] ) === 1 )
        {
            $sessions[] = $session ;
        }
    }

return $sessions ; }

function getSessionValues ( $session , $keys )
/*
 *  for a given session, return the key => value for all of the requested $keys  
 *
 *  $session    string
 *  $keys       [array]
 */
{   global $allSessions ;

    $keyValues = [] ;
    $thisSession = $allSessions[$session] ;
    foreach ( $keys as $key )
    {
        $keyValues[$key] = $thisSession[$key]['value'] ;
    }

return $keyValues ; }

function getSessionKeys ( $session )
/*
 *  for a given session, return all the keys
 */
{   global $allSessions ;
return array_keys( $allSessions[$session] ) ; }

function sessionHasKey ( $key )
/*
 *  Find all sessions with $key
 */
{   global $allSessions ;

    $sessions = [] ;
    foreach ( $allSessions as $session => $values )
    {
        if ( in_array( $key , array_keys( $values ) ) )
        {
            $sessions[] = $session ;
        }
    }

return $sessions ; }

function sessionDelete ( $session )
/*
 *  remove the sess_ file for a given session
 */
{
    $success = unlink( session_save_path() . '/' . $session ) ;
return $success ; }

function loadSessions ()
/*
 *  loads all sessions having key => values into an array
 */
{   global $allSessions ;

    $sessions = dirList( session_save_path() ) ;
    foreach ( $sessions as $oneSession )
    {
        $thisSession = file_get_contents( session_save_path() . '/' . $oneSession ) ;
        if ( strlen( $thisSession ) === 0 || $thisSession === false ) continue ; 

        $sessionParts = explode( ';' , $thisSession ) ;
        array_pop( $sessionParts ) ;
        foreach ( $sessionParts as $sessionSetting )
        {
            list( $key , $values ) = explode( '|' , $sessionSetting ) ;
            list( $type , $length , $value ) = explode( ':' , $values , 3 ) ;
            $value = substr( $value , 1 , -1 ) ;
            $allSessions[$oneSession][$key] = [ "type" => (string) $type , "length" => (int) $length , "value" => $value ] ;
        }
    }
}

/*
 *  setup & init
 */

$allSessions = [] ;
loadSessions() ;

?>
