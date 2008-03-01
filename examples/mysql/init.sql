begin;

create database mysql_test;
create user mysql_test;
grant all privileges on mysql_test.* to mysql_test@localhost;
	
create table test
(
	id int auto_increment primary key,
	name text,
	value text
);

insert into test (name, value) values ('one', 'first');
insert into test (name, value) values ('two', 'second');

commit;