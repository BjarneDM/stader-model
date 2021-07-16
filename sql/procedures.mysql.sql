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


