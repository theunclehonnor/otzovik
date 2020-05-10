--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: migration_versions; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public.migration_versions (
    version character varying(14) NOT NULL,
    executed_at timestamp(0) without time zone NOT NULL
);


ALTER TABLE public.migration_versions OWNER TO postgres;

--
-- Name: COLUMN migration_versions.executed_at; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.migration_versions.executed_at IS '(DC2Type:datetime_immutable)';


--
-- Name: user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    email character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public."user" OWNER TO postgres;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO postgres;

--
-- Data for Name: migration_versions; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.migration_versions (version, executed_at) FROM stdin;
20200507125721	2020-05-07 12:59:42
20200507205205	2020-05-07 20:53:37
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public."user" (id, email, roles, password, name) FROM stdin;
12	admin@mail.ru	["ROLE_ADMIN"]	$argon2id$v=19$m=65536,t=4,p=1$9O3McnXUU5ZxVqTEBu1iVg$N9scBU5pV6/ReNsfeq7OgZtUHyIwVVdh+bxtaL51zkU	Admin
13	user@mail.ru	["ROLE_USER"]	$argon2id$v=19$m=65536,t=4,p=1$x2ko+KoZajf09qmU3CYfUA$nVrS6DaoenqTq6ZnKtSiETfyMUVWyZT6+Zq+Ro4O3ok	User
16	osminog@mail.ru	["ROLE_USER"]	$argon2id$v=19$m=65536,t=4,p=1$cXoI3cxlEZzoMcCMkHKs5g$WtzyCv63YWC2XCg8iYv1Ioa/Mlup2ikOzYw3WVC+9vw	osminog
17	artem@mail.ru	["ROLE_USER"]	$argon2id$v=19$m=65536,t=4,p=1$awR3ag493reInYLBoQ+yUQ$Y3UTNjWnpbvGwClyCrvsDFErpkdravphAeiQNOkIOAQ	artem
19	lol@mail.ru	["ROLE_USER"]	$argon2id$v=19$m=65536,t=4,p=1$rUKUaPlerWdgwrpzx4eOIQ$FODsdH4qE3UkbXci0AJloGDZiQH+JPydqG1+uyG3e7Y	lololololololololololololololololol
20	faf@mail.ru	["ROLE_USER"]	$argon2id$v=19$m=65536,t=4,p=1$XG5p2qX4/ppNujSjVN13hQ$J3rmny/Z2YZQgAnGWgPgJyOGIYEzeStbsmiThk+Q1RY	faf
\.


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.user_id_seq', 20, true);


--
-- Name: migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public.migration_versions
    ADD CONSTRAINT migration_versions_pkey PRIMARY KEY (version);


--
-- Name: user_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: uniq_8d93d649e7927c74; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE UNIQUE INDEX uniq_8d93d649e7927c74 ON public."user" USING btree (email);


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

