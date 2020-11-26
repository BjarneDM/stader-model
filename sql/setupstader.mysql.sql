#
#   stader
#
drop database if exists stader ;
create database if not exists `stader` default character set utf8mb4 collate utf8mb4_unicode_ci ;

use stader ;

#
# create tables
#

# drop table if exists users ;
# create table if not exists users
# (
#     user_id     int auto_increment primary key ,
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
    user_id     int primary key ,
    salt        varchar(255) ,
    algo        varchar(255) ,
    tag         varchar(255) ,
    data        text
) ;

create table if not exists type_byte
(
    type_byte_id        int auto_increment primary key ,
    name                varchar(255) ,
        constraint unique (name)
) ;

create table if not exists ticket_status
(
    ticket_status_id    int auto_increment primary key,
    name                varchar(255) ,
        constraint unique (name) ,
    default_colour      varchar(255) ,
    description         text ,
    type_byte_id        int ,
        foreign key (type_byte_id) references type_byte(type_byte_id)
        on update cascade
        on delete restrict
) ;

create table if not exists place_owner
(
    place_owner_id  int auto_increment primary key ,
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
    area_id     int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description text
) ;

create table if not exists places
(
    place_id        int auto_increment primary key ,
    place_nr        varchar(8) not null ,
    description     text ,
    place_owner_id  int default null ,
        foreign key (place_owner_id) references place_owner(place_owner_id)
        on update cascade 
        on delete restrict ,
    area_id         int ,
        foreign key (area_id) references areas(area_id)
        on update cascade 
        on delete cascade ,
    lastchecked     datetime
        default   null ,
    active          bool default true ,

    unique key (place_nr,area_id)
) ;

create table if not exists tickets
(
    ticket_id           int auto_increment primary key ,
    header              varchar(255) not null ,
    description         text not null ,
    assigned_place_id   int ,
        foreign key (assigned_place_id) references places(place_id)
        on update cascade 
        on delete restrict ,
    ticket_status_id    int not null ,
        foreign key (ticket_status_id) references ticket_status(ticket_status_id)
        on update cascade 
        on delete restrict ,
    assigned_user_id    int default null ,
        foreign key (assigned_user_id) references userscrypt(user_id)
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
    group_id    int auto_increment primary key ,
    name        varchar(255) not null ,
        constraint unique (name) ,
    description varchar(255) 
) ;

create table if not exists users_groups
(
    users_groups_id     int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references user_groups(group_id)
        on update cascade 
        on delete restrict
) ;

create table if not exists ticket_group
(
    ticket_group_id     int auto_increment primary key ,
    ticket_id           int ,
        foreign key (ticket_id) references tickets(ticket_id)
        on update cascade 
        on delete cascade ,
    group_id            int ,
        foreign key (group_id) references user_groups(group_id)
        on update cascade 
        on delete restrict
) ;

create table if not exists beredskab
(
    beredskab_id    int auto_increment primary key ,
    message         text not null ,
    header          text ,
    created_by_id   int not null ,
        foreign key (created_by_id) references userscrypt(user_id)
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
    user_beredskab_id   int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete cascade ,
    beredskab_id        int ,
        foreign key (beredskab_id) references beredskab(beredskab_id)
        on update cascade 
        on delete cascade
) ;

create table if not exists roles
(
    role_id     int auto_increment primary key ,
    role        varchar(255) not null ,
        constraint unique (role) ,
    priority    int ,
        index (priority) ,
    note        text
) ;

create table if not exists users_roles
(
    users_roles_id     int auto_increment primary key ,
    user_id             int ,
        foreign key (user_id) references userscrypt(user_id)
        on update cascade 
        on delete cascade ,
    role_id            int ,
        foreign key (role_id) references roles(role_id)
        on update cascade 
        on delete cascade
) ;


create table if not exists flags
(
    flag_id     int auto_increment primary key ,
    text        varchar(255) ,
        constraint unique (text) ,     
    unicode     char(6) 
) ;



drop event if exists expire_tickets ;
create event expire_tickets
on schedule every 15 minute
starts current_timestamp
do
    update tickets as t ,
    (
        select t.ticket_id from ticket_status as ts , tickets as t 
        where   ts.ticket_status_id = t.ticket_status_id 
            and ts.name = 'OK'
            and t.active = true
            and timestampdiff( hour , t.lastupdatetime , current_timestamp ) > 3
    ) as too_old 
    set t.active = false
    where t.ticket_id = too_old.ticket_id
;


delimiter |

drop event if exists drop_tmp_tables |
create event drop_tmp_tables
on schedule every 5 minute
starts current_timestamp
do
    begin
        declare bDone int ;
        declare tName varchar(64) ;
        declare cTime timestamp ;

        declare curs cursor for
            select table_name , create_time
            from information_schema.tables  
            where   table_schema = "stader"       
                and table_name like "tmp_%"
        ;
        declare continue handler for not found set bDone = 1 ;

        open curs ;

        set bDone = 0 ;
        repeat
            fetch curs into tName , cTime ;
            if timestampdiff( minute , cTime , current_timestamp ) > 5
            then
                drop table tName ;
            end if ;
        until bDone end repeat ;
    end ;
|

delimiter ;

delimiter |

drop procedure if exists tmp_table_drop |
create procedure tmp_table_drop()
begin
    declare bDone int ;
    declare tName varchar(64) ;
    declare cTime timestamp ;

    declare curs cursor for
        select table_name , create_time
        from information_schema.tables  
        where   table_schema = "stader"       
            and table_name like "tmp_%"
    ;
    declare continue handler for not found set bDone = 1 ;

    open curs ;

    set bDone = 0 ;
    repeat
        fetch curs into tName , cTime ;
        select 1 ;
        if timestampdiff( minute , cTime , current_timestamp ) > 5
        then
            select 2 ;
        end if ;
    until bDone end repeat ;
end ;
|

delimiter ;


set foreign_key_checks = 1 ;




