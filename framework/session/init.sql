-- postgresql

begin;

CREATE TABLE session_base
(
	session_id text NOT NULL PRIMARY KEY,
	last_active timestamp with time zone NOT NULL
);

CREATE TABLE session_data
(
	session_id text NOT NULL,
	key text,
	value text,
	CONSTRAINT session_data_pkey PRIMARY KEY (session_id, key)
);

commit;


-- sqlite

begin;

CREATE TABLE session_base
(
	session_id text NOT NULL PRIMARY KEY,
	last_active timestamp with time zone NOT NULL
);

CREATE TABLE session_data
(
	session_id text NOT NULL,
	key text,
	value text
);

commit;
