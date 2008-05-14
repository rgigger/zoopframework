<?php
class Migration_1 extends Migration
{
	function up()
	{
		$sql = "create table person
				(
					id serial primary key,
					username text not null unique,
					password text,
					firstname text,
					lastname text,
					email text
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table request
				(
					id serial primary key,
					name text,
					description text,
					owner_id int4 references person,
					completed boolean not null default 'f'
				)";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
		SqlAlterSchema("drop table request");
		SqlAlterSchema("drop table person");
	}
}
