<?php namespace Stader\Model\Tables\User ;

use \Stader\Model\Abstract\DataObjectDao ;
use \Stader\Model\DateTimeString ;
use \Stader\Model\Traits\DataObjectConstruct ;

/*

create table if not exists loginlog
(
    id              int auto_increment primary key ,
    user_id         int not null ,
        index(user_id) ,
    action          varchar(255) ,
        index(action) ,
    passwd          varchar(255) not null ,
    ip_addr         varchar(255) ,
    lastlogintime   datetime
        default     null ,
    lastloginfail   datetime
        default     null ,
    loginfailures   int default 0 
) ;

 */

class LoginLog extends DataObjectDao
{
    public static $dbType      = 'logs' ;
    public static $thisClass   = '\\Stader\\Model\\Tables\\User\\LoginLog' ;
    public static $allowedKeys = 
        [ 'user_id'       => 'int'      , 
          'action'        => 'varchar'  ,
          'username'      => 'varchar'  ,
          'passwd'        => 'varchar'  , 
          'email'         => 'varchar'  ,
          'ip_addr'       => 'varchar'  ,
          'lastlogintime' => 'datetime' ,
          'lastloginfail' => 'datetime' ,
          'loginfailures' => 'int' 
        ] ;
    public static $privateKeys = [] ;

    use DataObjectConstruct ;

}
