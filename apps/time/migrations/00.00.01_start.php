<?php
class Migration_00_00_01 extends Migration
{
	function up()
	{
		$sql = "create table person (
					id serial primary key, 
					firstname text,
					lastname text,
					username text,
					password text
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table project (
					id serial primary key, 
					name text
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table entry (
					id serial primary key, 
					person_id int4 references person,
					project_id int4 references project,
					starttime timestamp with time zone not null,
					endtime timestamp with time zone
				)";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
		SqlQuery("drop table entry");
		SqlQuery("drop table project");
		SqlQuery("drop table person");
	}
}
