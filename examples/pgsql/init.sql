create user pgsql_test;
create database pgsql_test with owner = pgsql_test;
\c pgsql_test

begin;
	
create table test
(
	id serial primary key,
	name text,
	value text
);

alter table test owner to pgsql_test;

insert into test (name, value) values ('one', 'first');
insert into test (name, value) values ('two', 'second');

commit;