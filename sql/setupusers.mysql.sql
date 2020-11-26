#
#   stader
#
drop database if exists stader ;
create database if not exists `stader` default character set utf8mb4 collate utf8mb4_unicode_ci ;

CREATE USER 'stader'@'localhost' IDENTIFIED WITH mysql_native_password ;
alter  user 'stader'@'localhost' identified by '9E%<,4W(]jeQXn[O%TUZOKm$' ;

revoke all privileges, grant option
    from 'stader'@'localhost'
;
grant all
    on table stader.*
    to 'stader'@'localhost'
;


#
#   logs
#
drop database if exists logs ;
create database if not exists `logs` default character set utf8mb4 collate utf8mb4_unicode_ci ;

create user 'logs'@'localhost' identified with mysql_native_password ;
alter  user 'logs'@'localhost' identified by '[i8Z~iwGdW%Vq}p@E3m579+#' ;

revoke all privileges, grant option
    from 'logs'@'localhost'
;
grant all
    on table logs.*
    to 'logs'@'localhost'
;
