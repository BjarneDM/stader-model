<?php namespace Stader\Model\Traits ;

trait LogNotify
{
    protected function notify ( string $action ) : void
    {   // echo basename( __file__ ) . " : " . __function__ . \PHP_EOL ;
        // echo $action . PHP_EOL ;
        // print_r( $this->valuesOld ) ;
        // print_r( $this->values ) ;

        switch( $action )
        {
            case 'create' :
                new $this->thisLog( [
                    "{$this->referenceID}" => $this->values['id'] ,
                    "{$this->descriptID}"  => $this->values["{$this->descriptID}"] ,
                    'old_value' => '' ,
                    'new_value' => json_encode( $this->values )
                    ] ) ;
                break ;
            case 'read' :
                break ;
            case 'update' :
                new $this->thisLog( [
                    "{$this->referenceID}" => $this->values['id'] ,
                    "{$this->descriptID}"  => $this->values["{$this->descriptID}"] ,
                    'old_value' => json_encode( array_diff( $this->valuesOld , $this->values ) ) ,
                    'new_value' => json_encode( array_diff( $this->values , $this->valuesOld ) )
                    ] ) ;
                break ;
            case 'delete' :
                new $this->thisLog( [
                    "{$this->referenceID}" => $this->values['id'] ,
                    "{$this->descriptID}"  => $this->values["{$this->descriptID}"] ,
                    'old_value' => json_encode( $this->values ) ,
                    'new_value' => ''
                    ] ) ;
                break ;
        }

    }

}

?>
