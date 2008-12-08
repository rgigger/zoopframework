<?php
class Migration_2 extends Migration
{
	function up()
	{
		$sql = "CREATE TABLE session_base
				(
					session_id text NOT NULL PRIMARY KEY,
					last_active timestamp with time zone NOT NULL
				)";
		SqlAlterSchema($sql);
		
		$sql = "CREATE TABLE session_data
				(
					session_id text NOT NULL,
					key text,
					value text,
					PRIMARY KEY (session_id, key)
				)";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
		SqlAlterSchema("DROP TABLE session_data");
		SqlAlterSchema("DROP TABLE session_base");
	}
}