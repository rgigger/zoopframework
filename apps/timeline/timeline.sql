BEGIN;

CREATE TABLE entry
(
    id serial primary key,
    "start" timestamp with time zone,
    "end" timestamp with time zone,
    is_duration boolean,
    title text
);

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
	PRIMARY KEY (session_id, key)
);

create table session_form
(
    id serial primary key,
    session_id text not null,
    fields text not null
);

COMMIT;
