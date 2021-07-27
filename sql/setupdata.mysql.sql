#
#   staderdata
#
drop database if exists staderdata ;
create database if not exists `staderdata` 
    default character set utf8mb4 
    collate utf8mb4_unicode_ci 
;

use staderdata ;

#
# create tables
#

# denneher er farlig
# set foreign_key_checks = 0 ;

# drop tabellermne i den rigtige reækkefølge
drop table if exists ticketgroup ;
drop table if exists usergroup ;
drop table if exists ugroup ;
drop table if exists userberedskab ;
drop table if exists userrole ;
drop table if exists role ;
drop table if exists beredskab ;
drop table if exists ticket ;
drop table if exists place ;
drop table if exists area ;
drop table if exists placeowner ;
drop table if exists ticketstatus ;
drop table if exists typebyte ;
drop table if exists user ;
drop table if exists userscrypt ;
drop table if exists flag ;


create table if not exists user
(
    id          int auto_increment primary key ,
    name        varchar(255) not null ,
    surname     varchar(255) not null ,
    phone       varchar(255) not null ,
    username    varchar(255) not null ,
        constraint unique (username) ,
    passwd      varchar(255) not null ,
    email       varchar(255) not null ,
        constraint unique (email)
) ;
# ) engine = memory ;

# create tabellermne i den rigtige reækkefølge
create table if not exists usercrypt
(
    id      int primary key ,
    salt    varchar(255) ,
    algo    varchar(255) ,
    tag     varchar(255) ,
    data    text
) ;

create table if not exists typebyte
(
    id      int auto_increment primary key ,
    name    varchar(255) ,
        constraint unique (name)
) ;

create table if not exists ticketstatus
(
    id                  int auto_increment primary key,
    name                varchar(255) ,
        constraint unique (name) ,
    default_colour      varchar(255) ,
    description         text ,
    type_byte_id        int ,
        foreign key (type_byte_id) references typebyte(id)
        on update cascade
        on delete restrict
) ;

create table if not exists placeowner
(
    id              int auto_increment primary key ,
    name            varchar(255) not null ,
    surname         varchar(255) not null ,
    phone           varchar(255) not null ,
    email           varchar(255) not null ,
    organisation    varchar(255) not null
) ;

# insert into place_owner
#         ( name , surname , phone , email , organisation )
#     values
#         ( 'dummy' , '' , '' , '' , 'dummy' )
# ;

create table if not exists area
(
    id          int auto_increment ,
        index(id) ,
    name        varchar(255) not null primary key ,
        constraint unique (name) ,
    description text
) ;

create table if not exists place
(
    id              int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int default null ,
        foreign key (place_owner_id) references placeowner(id)
        on update cascade 
        on delete set null ,
    area_id         int ,
        foreign key (area_id) references area(id)
        on update cascade 
        on delete cascade ,
    lastchecked     datetime
        default   null ,
    active          bool default true ,

    unique key (place_nr,area_id)
) ;

create table if not exists ticket
(
    id                  int auto_increment primary key ,
    header              varchar(255) not null ,
    description         text not null ,
    assigned_place_id   int ,
        foreign key (assigned_place_id) references place(id)
        on update cascade 
        on delete restrict ,
    ticket_status_id    int not null ,
        foreign key (ticket_status_id) references ticketstatus(id)
        on update cascade 
        on delete restrict ,
    assigned_user_id    int default null ,
        foreign key (assigned_user_id) references user(id)
        on update cascade 
        on delete restrict ,
    creationtime        datetime
        default   current_timestamp ,
    lastupdatetime       datetime
        default   current_timestamp
        on update current_timestamp ,
    active              boolean default true
) ;

create table if not exists ugroup
(
    id          int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

create table if not exists usergroup
(
    id                  int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references user(id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references ugroup(id)
        on update cascade 
        on delete restrict
) ;

create table if not exists ticketgroup
(
    id                  int auto_increment primary key ,
    ticket_id           int ,
        foreign key (ticket_id) references ticket(id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references usergroup(id)
        on update cascade 
        on delete restrict
) ;

create table if not exists beredskab
(
    id              int auto_increment primary key ,
    message         text not null ,
    header          text ,
    created_by_id   int not null ,
        foreign key (created_by_id) references user(id)
        on update cascade 
        on delete restrict ,
    active          boolean default true ,
    creationtime    datetime
        default current_timestamp ,
    colour          varchar(16) default 'red' ,
    flag            varchar(255) default null
) ;

create table if not exists userberedskab
(
    id              int auto_increment primary key ,
    user_id         int ,
        foreign key (user_id) references user(id)
        on update cascade 
        on delete cascade ,
    beredskab_id    int ,
        foreign key (beredskab_id) references beredskab(id)
        on update cascade 
        on delete cascade
) ;

create table if not exists urole
(
    id          int auto_increment primary key ,
    role        varchar(255) not null ,
        constraint unique (role) ,
    priority    int ,
        index (priority) ,
    note        text
) ;

create table if not exists userrole
(
    id          int auto_increment primary key ,
    user_id     int ,
        foreign key (user_id) references user(id)
        on update cascade 
        on delete cascade ,
    role_id     int ,
        foreign key (role_id) references urole(id)
        on update cascade 
        on delete cascade
) ;

create table if not exists flag
(
    id          int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;


set foreign_key_checks = 1 ;




