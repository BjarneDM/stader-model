#
#   staderdata
#
# drop database if exists staderdata ;
create database if not exists `staderdata` default character set utf8mb4 collate utf8mb4_unicode_ci ;

create user 'staderdata'@'localhost' identified WITH mysql_native_password ;
alter  user 'staderdata'@'localhost' identified by '9E%<,4W(]jeQXn[O%TUZOKm$' ;

revoke all privileges, grant option
    from 'staderdata'@'localhost'
;
grant all
    on table staderdata.*
    to 'staderdata'@'localhost'
;


#
#   staderlogs
#
# drop database if exists staderlogs ;
create database if not exists `staderlogs` default character set utf8mb4 collate utf8mb4_unicode_ci ;

create user 'staderlogs'@'localhost' identified with mysql_native_password ;
alter  user 'staderlogs'@'localhost' identified by '[i8Z~iwGdW%Vq}p@E3m579+#' ;

revoke all privileges, grant option
    from 'staderlogs'@'localhost'
;
grant all
    on table staderlogs.*
    to 'staderlogs'@'localhost'
;
