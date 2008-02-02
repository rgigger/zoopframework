<?php
class Migration_00_00_02 extends Migration
{
	function up()
	{
		$sql = "create index word_letter_word_id_idx on word_letter (word_id)";
		SqlAlterSchema($sql);
	}
	
	function down()
	{
		SqlAlterSchema("drop index word_letter_word_id_idx");
	}
}
