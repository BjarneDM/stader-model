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

# drop table if exists users ;
# create table if not exists users
# (
#     id          int auto_increment primary key ,
#     name        varchar(255) not null ,
#     surname     varchar(255) not null ,
#     phone       varchar(255) not null ,
#     username    varchar(255) not null ,
#         constraint unique (username) ,
#     passwd      varchar(255) not null ,
#     email       varchar(255) not null ,
#         constraint unique (email)
# ) engine = memory ;

# denneher er farlig
# set foreign_key_checks = 0 ;

# drop tabellermne i den rigtige reækkefølge
drop table if exists ticket_group ;
drop table if exists users_groups ;
drop table if exists user_groups ;
drop table if exists user_beredskab ;
drop table if exists users_roles ;
drop table if exists roles ;
drop table if exists beredskab ;
drop table if exists tickets ;
drop table if exists places ;
drop table if exists areas ;
drop table if exists place_owner ;
drop table if exists ticket_status ;
drop table if exists type_byte ;
drop table if exists userscrypt ;
drop table if exists flags ;


# create tabellermne i den rigtige reækkefølge
create table if not exists userscrypt
(
    id      int primary key ,
    salt    varchar(255) ,
    algo    varchar(255) ,
    tag     varchar(255) ,
    data    text
) ;

create table if not exists type_byte
(
    id      int auto_increment primary key ,
    name    varchar(255) ,
        constraint unique (name)
) ;

create table if not exists ticket_status
(
    id                  int auto_increment primary key,
    name                varchar(255) ,
        constraint unique (name) ,
    default_colour      varchar(255) ,
    description         text ,
    type_byte_id        int ,
        foreign key (type_byte_id) references type_byte(id)
        on update cascade
        on delete restrict
) ;

create table if not exists place_owner
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

create table if not exists areas
(
    id          int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description text
) ;

create table if not exists places
(
    id              int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int default null ,
        foreign key (place_owner_id) references place_owner(id)
        on update cascade 
        on delete restrict ,
    area_id         int ,
        foreign key (area_id) references areas(id)
        on update cascade 
        on delete cascade ,
    lastchecked     datetime
        default   null ,
    active          bool default true ,

    unique key (place_nr,area_id)
) ;

create table if not exists tickets
(
    id                  int auto_increment primary key ,
    header              varchar(255) not null ,
    description         text not null ,
    assigned_place_id   int ,
        foreign key (assigned_place_id) references places(id)
        on update cascade 
        on delete restrict ,
    ticket_status_id    int not null ,
        foreign key (ticket_status_id) references ticket_status(id)
        on update cascade 
        on delete restrict ,
    assigned_user_id    int default null ,
        foreign key (assigned_user_id) references userscrypt(id)
        on update cascade 
        on delete restrict ,
    creationtime        datetime
        default   current_timestamp ,
    lastupdatetime       datetime
        default   current_timestamp
        on update current_timestamp ,
    active              boolean default true
) ;

create table if not exists user_groups
(
    id          int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

create table if not exists users_groups
(
    id                  int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references userscrypt(id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references user_groups(id)
        on update cascade 
        on delete restrict
) ;

create table if not exists ticket_group
(
    id                  int auto_increment primary key ,
    ticket_id           int ,
        foreign key (ticket_id) references tickets(id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references user_groups(id)
        on update cascade 
        on delete restrict
) ;

create table if not exists beredskab
(
    id              int auto_increment primary key ,
    message         text not null ,
    header          text ,
    created_by_id   int not null ,
        foreign key (created_by_id) references userscrypt(id)
        on update cascade 
        on delete restrict ,
    active          boolean default true ,
    creationtime    datetime
        default current_timestamp ,
    colour          varchar(16) default 'red' ,
    flag            varchar(255) default null
) ;

create table if not exists user_beredskab
(
    id              int auto_increment primary key ,
    user_id         int ,
        foreign key (user_id) references userscrypt(id)
        on update cascade 
        on delete cascade ,
    beredskab_id    int ,
        foreign key (beredskab_id) references beredskab(id)
        on update cascade 
        on delete cascade
) ;

create table if not exists roles
(
    id          int auto_increment primary key ,
    role        varchar(255) not null ,
        constraint unique (role) ,
    priority    int ,
        index (priority) ,
    note        text
) ;

create table if not exists users_roles
(
    id          int auto_increment primary key ,
    user_id     int ,
        foreign key (user_id) references userscrypt(id)
        on update cascade 
        on delete cascade ,
    role_id     int ,
        foreign key (role_id) references roles(id)
        on update cascade 
        on delete cascade
) ;


create table if not exists flags
(
    id          int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;


set foreign_key_checks = 1 ;




