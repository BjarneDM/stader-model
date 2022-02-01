<?php   namespace Stader ;
/*
 *  phpunit vil have denne fil er til stede, men synes ikke at bruge den til noget, når det kommer til stykket
 */

spl_autoload_register(
    function ( $theClass )
    {   // echo 'spl_autoload_register  namespace Stader' . \PHP_EOL ;
        // echo  $theClass . \PHP_EOL ;

        $oldDir = getcwd() ;
        chdir( __dir__ ) ;

        $className = explode( '\\', $theClass ) ;
        switch ( PHP_OS_FAMILY )
        {
            case 'Windows' :
                $className = end( $className ) ;
                $index = 3 ;
                exec("powershell.exe -Command \" get-childitem '" . __dir__ . "/src/{$className[1]}' -include '" . end($className) . ".php' -Recurse | Select-Object FullName \" ", $classFile);
                break;
            default :
                $index = 0 ;
                // exec( "find 'src/{$className[1]}' -type f -name '{$className}.php' " , $classFile ) ;
                $className[0] = 'src' ;
                $classFile[$index] = implode( '/' , $className ) . '.php' ;
                break ;
        }

        try {
            if ( ! empty ( $classFile ) )  {
                require_once( $classFile[ $index ] ) ;
            } else {
                throw new \Exception( end( $className ) . " findes ikke i 'Stader\\{$className[1]}' namespacet" ) ;
            }
        } catch( \Exception $e ) 
        {   echo end( $className ) . " findes ikke i 'Stader\\{$className[1]}' namespacet" ;
            exit() ;
        }

        chdir( $oldDir ) ;
    }
) ;
?>
