<?php
function dirList ( ...$args )
{
    $files = array() ;
    if ( is_dir($args[0]) )
    {
        $files = scandir($args[0]) ;
        $files = preg_grep ( "/^(\.){1,2}$/" , $files , PREG_GREP_INVERT ) ;
        if ( isset($args[1]) ) $files = preg_grep( "/{$args[1]}/", $files ) ;
        $files = array_values($files) ;
    }
return $files ; }
?>
