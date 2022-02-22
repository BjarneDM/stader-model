#
#   staderlogs
#
drop database if exists staderlogs ;
create database if not exists `staderlogs` 
    default character set utf8mb4 
    collate utf8mb4_unicode_ci 
;

use staderlogs ;

#
# create tables
#

# denneher er farlig !!!
# bruges den, !!!SKAL!!! man huske den modsvarende kommando !!!
# set foreign_key_checks = 0 ;

drop table if exists loginlog ;
create table if not exists loginlog
(
    id              int auto_increment primary key ,
    user_id         int default null ,
        index(user_id) ,
    action          varchar(255) not null ,
        index(action) ,
    username        varchar(255) default null ,
    passwd          varchar(255) default null ,
    email           varchar(255) default null ,
    ip_addr         varchar(255) default null ,
    lastlogintime   datetime
        default     null ,
    lastloginfail   datetime
        default     null ,
    loginfailures   int default 0 
) ;

drop table if exists loginlogcrypt ;
create table if not exists loginlogcrypt
(
    id              int auto_increment primary key ,
    user_id         int default null ,
        index (user_id) ,
    salt            varchar(255) ,
    algo            varchar(255) ,
    tag             varchar(255) ,
    data            text
) ;

drop table if exists beredskablog ;
# create table if not exists beredskablog
# (
#     id                  int auto_increment primary key ,
#     beredskab_id        int ,
#         index (beredskab_id) ,
#     header              varchar(255) ,
#         index (header) ,
#     old_value           text default null ,
#     new_value           text default null ,
#     log_timestamp       datetime
#         default current_timestamp ,
#         index (log_timestamp)
# ) ;

drop table if exists ticketlog ;
# create table if not exists ticketlog
# (
#     id              int auto_increment primary key ,
#     ticket_id       int not null ,
#         index (ticket_id) ,
#     header          varchar(255) ,
#         index (header) ,
#     old_value       text default null ,
#     new_value       text default null ,
#     log_timestamp   datetime
#         default current_timestamp ,
#         index (log_timestamp)
# ) ;

drop table if exists placelog ;
# create table if not exists placelog
# (
#     id              int auto_increment primary key ,
#     place_id        int ,
#         index (place_id) ,
#     description     varchar(255) ,
#         index (description) ,
#     old_value       text default null ,
#     new_value       text default null ,
#     log_timestamp   datetime
#         default current_timestamp ,
#         index (log_timestamp)
# ) ;


set foreign_key_checks = 1 ;
