#
#   logs
#
drop database if exists logs ;
create database if not exists `logs` default character set utf8mb4 collate utf8mb4_unicode_ci ;

use logs ;

#
# create tables
#

set foreign_key_checks = 0 ;


drop table if exists ticket_log ;
create table if not exists ticket_log
(
    ticket_log_id   int auto_increment primary key ,
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

drop table if exists place_log ;
create table if not exists place_log
(
    place_log_id    int auto_increment primary key ,
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

drop table if exists beredskab_log ;
create table if not exists beredskab_log
(
    beredskab_log_id    int auto_increment primary key ,
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
