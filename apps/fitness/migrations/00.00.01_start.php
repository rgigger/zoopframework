<?php
class Migration_00_00_01 extends Migration
{
	function up()
	{
		$sql = "create table person (
					id serial primary key, 
					firstname text,
					lastname text
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table exercise (
					id serial primary key, 
					short text,
					long text
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table session (
					id serial primary key, 
					person_id int4 references person(id),
					date timestamp with time zone
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table unit (
					id serial primary key, 
					name text
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table session_exercise (
					id serial primary key, 
					session_id int4 references session(id),
					exercise_id int4 references exercise(id),
					unit_id int4 references unit(id),
					unit_count int4 not null default 0
				)";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
		SqlQuery("drop table session_exercise");
		SqlQuery("drop table session");
		SqlQuery("drop table exercise");
	}
}