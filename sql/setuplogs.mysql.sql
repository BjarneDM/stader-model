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

set foreign_key_checks = 0 ;


drop table if exists ticketlog ;
create table if not exists ticketlog
(
    id              int auto_increment primary key ,
    ticket_id       int not null ,
        index (ticket_id) ,
    header          varchar(255) ,
        index (header) ,
    log_timestamp   datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value       text default null ,
    new_value       text default null
) ;

drop table if exists placelog ;
create table if not exists placelog
(
    id              int auto_increment primary key ,
    place_id        int ,
        index (place_id) ,
    header          varchar(255) ,
        index (header) ,
    log_timestamp   datetime
        default current_timestamp ,
        index (log_timestamp) ,
    old_value       text default null ,
    new_value       text default null
) ;

drop table if exists beredskablog ;
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


set foreign_key_checks = 1 ;
