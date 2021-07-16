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

