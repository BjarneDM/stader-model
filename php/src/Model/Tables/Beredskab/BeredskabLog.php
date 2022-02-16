<?php namespace Stader\Model\Tables\Beredskab ;

use \Stader\Model\Abstract\LogObjectDao ;
use \Stader\Model\OurDateTime ;
use \Stader\Model\Traits\{DataObjectConstruct,LogFunctions} ;

/*

create table if not exists beredskablog
(
    id                  int auto_increment primary key ,
    beredskab_id        int ,
        index (beredskab_id) ,
    header              varchar(255) ,
        index (header) ,
    log_timestamp       datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value           text default null ,
    new_value           text default null
) ;

 */

class BeredskabLog extends LogObjectDao
{
    public static $allowedKeys = 
        [ 'beredskab_id' => 'int'     , 
          'header'       => 'varchar' , 
          'old_value'    => 'text'    , 
          'new_value'    => 'text'
        ] ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\Beredskab\\BeredskabLog' ;
    private $referenceID ;
    private $descriptID  ;

    use DataObjectConstruct ;
    use LogFunctions ;

}

?>
