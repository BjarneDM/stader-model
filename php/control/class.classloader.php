<?php   namespace stader\control ;
/*
 *  phpunit vil have denne fil er til stede, men synes ikke at bruge den til noget, når det kommer til stykket
 */
spl_autoload_register(
    function ( $theClass )
    {   // echo 'spl_autoload_register  namespace stader\control' . \PHP_EOL ;
        // echo  $theClass . \PHP_EOL ;

        $oldDir = getcwd() ;
        chdir( __dir__ ) ;

        $className = explode( '\\', $theClass ) ;
        if ( $className[1] === 'control' )
        {
            $className = end( $className ) ;

            switch ( PHP_OS_FAMILY )
            {
                case 'Windows' :
                    $index = 3 ;
                    exec("powershell.exe -Command \" get-childitem '". __dir__ ."' -include 'class.{$className}.php' -Recurse | Select-Object FullName \" ", $classFile);
                    break;
                default :
                    $index = 0 ;
                    exec( "find . -type f -iname 'class.{$className}.php' " , $classFile ) ;
                    break ;
            }

            if ( ! empty ( $classFile ) )  {
                require_once( $classFile[ $index ] ) ;
            } else {
                throw new \Exeption( "{$theClass} findes ikke i 'stader\control' namespacet" ) ;
            }
        }

        chdir( $oldDir ) ;
    }
) ;
?>
