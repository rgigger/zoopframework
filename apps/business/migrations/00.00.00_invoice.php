<?php
class Migration_00_00_00 extends Migration
{
	function up()
	{
		$sql = "create table client
				(
					id serial primary key,
					name text
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table invoice
				(
					id serial primary key,
					client_id int4 references client
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table invoice_item
				(
					id serial primary key,
					invoice_id int4 references invoice
				)";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
	}
}