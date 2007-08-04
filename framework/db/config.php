<?php
/*
DefineOnce('db_use_default_connection', 1);

if(db_use_default_connection)
{
	RequireDefined('db_driver');
}

switch(db_driver)
{
	case 'pgsql_php':
		RequireDefined('db_database');
		RequireDefined('db_username');
		DefineOnce('db_password', '');
		DefineOnce('db_host', 'localhost');
		DefineOnce('db_port', 5432);
		break;
	default:
		//	do nothing.  we want to allow them to create their own drivers to use as the default
		break;
}
*/