<?php
class Migration_00_00_03 extends Migration
{
	function up()
	{
		$sql = "create table board (id serial primary key, name text unique)";
		SqlAlterSchema($sql);
		SqlInsertRow("insert into board (name) values (:name)", array('name' => 'default'));
		
		$sql = "create table board_cell (
					id serial primary key, 
					board_id int4 references board,
					row_num int2 not null,
					col_num int2 not null,
					letter varchar(1) not null
				)";
		SqlAlterSchema($sql);
		
	}
	
	function down()
	{
		SqlAlterSchema("drop table board_cell");
		SqlAlterSchema("drop table board");
	}
}
