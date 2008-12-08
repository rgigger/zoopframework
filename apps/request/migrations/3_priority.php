<?php
class Migration_3 extends Migration
{
	function up()
	{
		
		$sql = "CREATE TABLE priority
				(
					id int2 primary key,
					name text
				)";
		SqlAlterSchema($sql);
		
		SqlQuery("insert into priority (id, name) values (1, 'Low')", array());
		SqlQuery("insert into priority (id, name) values (2, 'Medium')", array());
		SqlQuery("insert into priority (id, name) values (3, 'High')", array());
		
		$sql = "ALTER TABLE request add column priority_id int2 references priority not null default 1";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
		$sql = "ALTER TABLE request DROP column priority_id";
		SqlAlterSchema($sql);
		
		$sql = "DROP TABLE priority";
		SqlAlterSchema($sql);
	}
}