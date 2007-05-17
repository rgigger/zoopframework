<?php
class Migration_00_00_01 //extends Migration
{
	function up()
	{
		$sql = "create table exercise (
					id serial primary key, 
					short text,
					long text
				)";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
		SqlQuery("drop table exercise");
	}
}