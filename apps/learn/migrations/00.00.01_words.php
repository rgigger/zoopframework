<?php
class Migration_00_00_01 extends Migration
{
	function up()
	{
		$sql = "create table word (
					id serial primary key, 
					word text unique,
					len int2
				)";
		SqlAlterSchema($sql);
		
		$sql = "create table word_letter (
					id serial primary key, 
					word_id int4 references word,
					letter \"char\",
					count int2
				)";
		SqlAlterSchema($sql);	
	}
	
	function down()
	{
		SqlAlterSchema("drop table word_letter");
		SqlAlterSchema("drop table word");
	}
}
