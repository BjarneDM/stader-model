<?php   namespace Stader\Model ;
/*
 *  phpunit vil have denne fil er til stede, men synes ikke at bruge den til noget, når det kommer til stykket
 */
spl_autoload_register(
    function ( $theClass )
    {   // echo 'spl_autoload_register  namespace Stader\Model' . \PHP_EOL ;
        // echo  $theClass . \PHP_EOL ;

        $oldDir = getcwd() ;
        chdir( __dir__ ) ;

        $className = explode( '\\', $theClass ) ;
        if ( $className[1] === 'Model' )
        {

            switch ( PHP_OS_FAMILY )
            {
                case 'Windows' :
                    $className = end( $className ) ;
                    $index = 3 ;
                    exec("powershell.exe -Command \" get-childitem '". __dir__ ."' -include '$className}.php' -Recurse | Select-Object FullName \" ", $classFile);
                    break;
                default :
                    $index = 0 ;
                    unset( $className[0] , $className[1]) ;
                    $classFile[$index] = implode( '/' , $className ) . '.php' ;
                    break ;
            }

            if ( ! empty ( $classFile ) )  {
                require_once( $classFile[ $index ] ) ;
            } else {
                throw new \Exception( "{$theClass} findes ikke i 'Stader\Model' namespacet" ) ;
            }
        }

        chdir( $oldDir ) ;
    }
) ;
?>
