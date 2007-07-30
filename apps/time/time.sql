--
-- PostgreSQL database dump
--

SET client_encoding = 'UTF8';
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA public IS 'Standard public schema';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: entry; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE entry (
    id serial NOT NULL,
    person_id integer,
    project_id integer,
    starttime timestamp with time zone NOT NULL,
    endtime timestamp with time zone
);


ALTER TABLE public.entry OWNER TO postgres;

--
-- Name: entry_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('entry', 'id'), 8, true);


--
-- Name: migration; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE migration (
    id serial NOT NULL,
    name text NOT NULL,
    applied smallint DEFAULT 0 NOT NULL
);


ALTER TABLE public.migration OWNER TO postgres;

--
-- Name: migration_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('migration', 'id'), 1, true);


--
-- Name: person; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE person (
    id serial NOT NULL,
    firstname text,
    lastname text,
    username text,
    "password" text
);


ALTER TABLE public.person OWNER TO postgres;

--
-- Name: person_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('person', 'id'), 4, true);


--
-- Name: project; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE project (
    id serial NOT NULL,
    name text
);


ALTER TABLE public.project OWNER TO postgres;

--
-- Name: project_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval(pg_catalog.pg_get_serial_sequence('project', 'id'), 1, true);


--
-- Name: session_base; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE session_base (
    session_id text NOT NULL,
    last_active timestamp with time zone NOT NULL
);


ALTER TABLE public.session_base OWNER TO postgres;

--
-- Name: session_data; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE session_data (
    session_id text NOT NULL,
    "key" text NOT NULL,
    value text
);


ALTER TABLE public.session_data OWNER TO postgres;

--
-- Data for Name: entry; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY entry (id, person_id, project_id, starttime, endtime) FROM stdin;
8	1	1	2007-07-04 16:53:01-06	2007-07-04 17:53:01-06
\.


--
-- Data for Name: migration; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY migration (id, name, applied) FROM stdin;
1	00.00.01	1
\.


--
-- Data for Name: person; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY person (id, firstname, lastname, username, "password") FROM stdin;
1	Rick	Gigger	rick	test
2	John	LeSueur	john	test
3	James	Thayne	james	test
4	Ryan	Hatch	ryan	test
\.


--
-- Data for Name: project; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY project (id, name) FROM stdin;
1	NASA
\.


--
-- Data for Name: session_base; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY session_base (session_id, last_active) FROM stdin;
e8svfg6tthfdg1bptpu0fnn9e3	2007-07-04 15:53:05.551907-06
\.


--
-- Data for Name: session_data; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY session_data (session_id, "key", value) FROM stdin;
e8svfg6tthfdg1bptpu0fnn9e3	__default__	personId|s:1:"1";
\.


--
-- Name: entry_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY entry
    ADD CONSTRAINT entry_pkey PRIMARY KEY (id);


--
-- Name: migration_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY migration
    ADD CONSTRAINT migration_pkey PRIMARY KEY (id);


--
-- Name: person_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY person
    ADD CONSTRAINT person_pkey PRIMARY KEY (id);


--
-- Name: project_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY project
    ADD CONSTRAINT project_pkey PRIMARY KEY (id);


--
-- Name: session_base_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY session_base
    ADD CONSTRAINT session_base_pkey PRIMARY KEY (session_id);


--
-- Name: session_data_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY session_data
    ADD CONSTRAINT session_data_pkey PRIMARY KEY (session_id, "key");


--
-- Name: entry_person_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entry
    ADD CONSTRAINT entry_person_id_fkey FOREIGN KEY (person_id) REFERENCES person(id);


--
-- Name: entry_project_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY entry
    ADD CONSTRAINT entry_project_id_fkey FOREIGN KEY (project_id) REFERENCES project(id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

