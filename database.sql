--
-- PostgreSQL database dump
--

-- Dumped from database version 10.3
-- Dumped by pg_dump version 10.3

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner:
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner:
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: postgis; Type: EXTENSION; Schema: -; Owner:
--

CREATE EXTENSION IF NOT EXISTS postgis WITH SCHEMA public;


--
-- Name: EXTENSION postgis; Type: COMMENT; Schema: -; Owner:
--

COMMENT ON EXTENSION postgis IS 'PostGIS geometry, geography, and raster spatial types and functions';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id integer NOT NULL,
    name text,
    api_enum text,
    cat_id text NOT NULL,
    z text NOT NULL,
    z_id_cat integer NOT NULL
);


ALTER TABLE public.categories OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.categories_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.categories_id_seq OWNER TO postgres;

--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: chat_rooms; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.chat_rooms (
    chat_room_id integer NOT NULL,
    event_id integer,
    name character varying(100) NOT NULL,
    creation_datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.chat_rooms OWNER TO postgres;

--
-- Name: chat_rooms_chat_room_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.chat_rooms_chat_room_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.chat_rooms_chat_room_id_seq OWNER TO postgres;

--
-- Name: chat_rooms_chat_room_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.chat_rooms_chat_room_id_seq OWNED BY public.chat_rooms.chat_room_id;


--
-- Name: errors; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.errors (
    id integer NOT NULL,
    user_id integer,
    message text,
    code integer,
    datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.errors OWNER TO postgres;

--
-- Name: errors_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.errors_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.errors_id_seq OWNER TO postgres;

--
-- Name: errors_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.errors_id_seq OWNED BY public.errors.id;


--
-- Name: events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.events (
    id integer NOT NULL,
    name character varying(250),
    creator_id integer NOT NULL,
    creator_latitude real,
    creator_longitude real,
    public boolean DEFAULT false NOT NULL,
    latitude real,
    longitude real,
    start_time timestamp(0) with time zone NOT NULL,
    end_time timestamp(0) with time zone,
    information text,
    picture_url character varying(500),
    creation_datetime timestamp(0) with time zone NOT NULL,
    with_localisation boolean NOT NULL
);


ALTER TABLE public.events OWNER TO postgres;

--
-- Name: events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.events_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.events_id_seq OWNER TO postgres;

--
-- Name: events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.events_id_seq OWNED BY public.events.id;


--
-- Name: events_member_details; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.events_member_details (
    id integer NOT NULL,
    event_id integer NOT NULL,
    invited_friend_id integer NOT NULL,
    friend_latitude real,
    friend_longitude real,
    response integer,
    creator boolean,
    datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.events_member_details OWNER TO postgres;

--
-- Name: events_member_details_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.events_member_details_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.events_member_details_id_seq OWNER TO postgres;

--
-- Name: events_member_details_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.events_member_details_id_seq OWNED BY public.events_member_details.id;


--
-- Name: events_requests; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.events_requests (
    id integer NOT NULL,
    event_id integer NOT NULL,
    friend_id integer NOT NULL,
    response integer DEFAULT 0 NOT NULL,
    creation_datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.events_requests OWNER TO postgres;

--
-- Name: events_requests_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.events_requests_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.events_requests_id_seq OWNER TO postgres;

--
-- Name: events_requests_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.events_requests_id_seq OWNED BY public.events_requests.id;


--
-- Name: friends_links; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.friends_links (
    id integer NOT NULL,
    user_id integer NOT NULL,
    friend_id integer NOT NULL,
    mutual_friends integer NOT NULL,
    mutual_likes integer NOT NULL,
    relation integer DEFAULT 0 NOT NULL
);


ALTER TABLE public.friends_links OWNER TO postgres;

--
-- Name: friends_links_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.friends_links_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.friends_links_id_seq OWNER TO postgres;

--
-- Name: friends_links_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.friends_links_id_seq OWNED BY public.friends_links.id;


--
-- Name: google_places; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.google_places (
    id integer NOT NULL,
    google_id text NOT NULL,
    name text NOT NULL,
    latitude real NOT NULL,
    longitude real NOT NULL,
    picture_url text,
    category text NOT NULL,
    day_0_open integer,
    day_0_close integer,
    day_0_invert integer DEFAULT 0 NOT NULL,
    day_1_open integer,
    day_1_close integer,
    day_1_invert integer DEFAULT 0 NOT NULL,
    day_2_open integer,
    day_2_close integer,
    day_2_invert integer DEFAULT 0 NOT NULL,
    day_3_open integer,
    day_3_close integer,
    day_3_invert integer DEFAULT 0 NOT NULL,
    day_4_open integer,
    day_4_close integer,
    day_4_invert integer DEFAULT 0 NOT NULL,
    day_5_open integer,
    day_5_close integer,
    day_5_invert integer DEFAULT 0 NOT NULL,
    day_6_open integer,
    day_6_close integer,
    day_6_invert integer DEFAULT 0 NOT NULL,
    creation_datetime timestamp(0) with time zone NOT NULL,
    json text,
    review double precision NOT NULL
);


ALTER TABLE public.google_places OWNER TO postgres;

--
-- Name: google_places_assoc; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.google_places_assoc (
    id integer NOT NULL,
    place_id integer NOT NULL,
    cat_id integer NOT NULL
);


ALTER TABLE public.google_places_assoc OWNER TO postgres;

--
-- Name: google_places_assoc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.google_places_assoc_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.google_places_assoc_id_seq OWNER TO postgres;

--
-- Name: google_places_assoc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.google_places_assoc_id_seq OWNED BY public.google_places_assoc.id;


--
-- Name: google_places_cat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.google_places_cat (
    id integer NOT NULL,
    name text NOT NULL,
    cat_id integer NOT NULL
);


ALTER TABLE public.google_places_cat OWNER TO postgres;

--
-- Name: google_places_cat_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.google_places_cat_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.google_places_cat_id_seq OWNER TO postgres;

--
-- Name: google_places_cat_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.google_places_cat_id_seq OWNED BY public.google_places_cat.id;


--
-- Name: google_places_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.google_places_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.google_places_id_seq OWNER TO postgres;

--
-- Name: google_places_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.google_places_id_seq OWNED BY public.google_places.id;


--
-- Name: hack_attempts; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.hack_attempts (
    id integer NOT NULL,
    ip character varying(20) NOT NULL,
    request character varying(250) NOT NULL,
    key_app_intented character varying(250),
    creation_datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.hack_attempts OWNER TO postgres;

--
-- Name: hack_attempts_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.hack_attempts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.hack_attempts_id_seq OWNER TO postgres;

--
-- Name: hack_attempts_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.hack_attempts_id_seq OWNED BY public.hack_attempts.id;


--
-- Name: logs; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.logs (
    id integer NOT NULL,
    user_id integer,
    operation character varying(100) NOT NULL,
    ip character varying(15),
    datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.logs OWNER TO postgres;

--
-- Name: logs_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.logs_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.logs_id_seq OWNER TO postgres;

--
-- Name: logs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.logs_id_seq OWNED BY public.logs.id;


--
-- Name: members; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.members (
    id integer NOT NULL,
    key_app character varying(100) NOT NULL,
    fb_access_token character varying(250) NOT NULL,
    fb_user_id character varying(250) NOT NULL,
    expire timestamp(0) with time zone NOT NULL,
    name character varying(200),
    first_name character varying(200),
    last_name character varying(200),
    profile_picture_url character varying(500),
    latitude real,
    longitude real,
    last_latitude real,
    last_longitude real,
    unavailable timestamp(0) with time zone,
    gcm_registration_id text,
    location_last_check_up timestamp(0) with time zone,
    creation_datetime timestamp(0) with time zone NOT NULL,
    last_add_events timestamp(0) with time zone,
    last_add_events_google timestamp(0) with time zone
);


ALTER TABLE public.members OWNER TO postgres;

--
-- Name: members_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.members_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.members_id_seq OWNER TO postgres;

--
-- Name: members_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.members_id_seq OWNED BY public.members.id;


--
-- Name: messages; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.messages (
    message_id integer NOT NULL,
    user_id integer NOT NULL,
    chat_room_id integer NOT NULL,
    message text NOT NULL,
    creation_datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.messages OWNER TO postgres;

--
-- Name: messages_message_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.messages_message_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.messages_message_id_seq OWNER TO postgres;

--
-- Name: messages_message_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.messages_message_id_seq OWNED BY public.messages.message_id;


--
-- Name: place_events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.place_events (
    id integer NOT NULL,
    fb_id character varying(255),
    name text NOT NULL,
    about text,
    emails text,
    picture text,
    cover text,
    category text,
    category_list text,
    city character varying(250),
    country character varying(250),
    street character varying(250),
    zip character varying(250),
    latitude real,
    longitude real
);


ALTER TABLE public.place_events OWNER TO postgres;

--
-- Name: place_events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.place_events_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.place_events_id_seq OWNER TO postgres;

--
-- Name: place_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.place_events_id_seq OWNED BY public.place_events.id;


--
-- Name: places_cat; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.places_cat (
    id integer NOT NULL,
    place_id character varying(255) NOT NULL,
    z_id_cat integer NOT NULL
);


ALTER TABLE public.places_cat OWNER TO postgres;

--
-- Name: places_cat_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.places_cat_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.places_cat_id_seq OWNER TO postgres;

--
-- Name: places_cat_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.places_cat_id_seq OWNED BY public.places_cat.id;


--
-- Name: public_events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.public_events (
    id integer NOT NULL,
    fb_id character varying(255),
    place_id character varying(255) NOT NULL,
    name text NOT NULL,
    cover text,
    picture text,
    description text,
    start_time timestamp(0) with time zone NOT NULL,
    end_time timestamp(0) with time zone NOT NULL,
    attending_count integer,
    declined_count integer,
    maybe_count integer,
    noreply_count integer,
    is_canceled boolean,
    ticket_uri text,
    ticketing_terms_uri text,
    ticketing_privacy_uri text,
    is_draft boolean,
    creation_datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.public_events OWNER TO postgres;

--
-- Name: public_events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.public_events_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.public_events_id_seq OWNER TO postgres;

--
-- Name: public_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.public_events_id_seq OWNED BY public.public_events.id;


--
-- Name: researchable_events; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.researchable_events (
    id integer NOT NULL,
    name character varying(250) NOT NULL,
    creator_id integer NOT NULL,
    latitude real NOT NULL,
    longitude real NOT NULL,
    information character varying(500),
    picture_url character varying(500) NOT NULL,
    category character varying(100),
    start_date date NOT NULL,
    end_date date NOT NULL,
    start_time time without time zone NOT NULL,
    end_time time without time zone NOT NULL,
    repeat boolean DEFAULT false NOT NULL,
    repeat_type character varying(20) DEFAULT 'false'::character varying,
    creation_datetime timestamp(0) with time zone NOT NULL
);


ALTER TABLE public.researchable_events OWNER TO postgres;

--
-- Name: researchable_events_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.researchable_events_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.researchable_events_id_seq OWNER TO postgres;

--
-- Name: researchable_events_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.researchable_events_id_seq OWNED BY public.researchable_events.id;


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: chat_rooms chat_room_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chat_rooms ALTER COLUMN chat_room_id SET DEFAULT nextval('public.chat_rooms_chat_room_id_seq'::regclass);


--
-- Name: errors id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.errors ALTER COLUMN id SET DEFAULT nextval('public.errors_id_seq'::regclass);


--
-- Name: events id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events ALTER COLUMN id SET DEFAULT nextval('public.events_id_seq'::regclass);


--
-- Name: events_member_details id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events_member_details ALTER COLUMN id SET DEFAULT nextval('public.events_member_details_id_seq'::regclass);


--
-- Name: events_requests id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events_requests ALTER COLUMN id SET DEFAULT nextval('public.events_requests_id_seq'::regclass);


--
-- Name: friends_links id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.friends_links ALTER COLUMN id SET DEFAULT nextval('public.friends_links_id_seq'::regclass);


--
-- Name: google_places id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.google_places ALTER COLUMN id SET DEFAULT nextval('public.google_places_id_seq'::regclass);


--
-- Name: google_places_assoc id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.google_places_assoc ALTER COLUMN id SET DEFAULT nextval('public.google_places_assoc_id_seq'::regclass);


--
-- Name: google_places_cat id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.google_places_cat ALTER COLUMN id SET DEFAULT nextval('public.google_places_cat_id_seq'::regclass);


--
-- Name: hack_attempts id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hack_attempts ALTER COLUMN id SET DEFAULT nextval('public.hack_attempts_id_seq'::regclass);


--
-- Name: logs id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs ALTER COLUMN id SET DEFAULT nextval('public.logs_id_seq'::regclass);


--
-- Name: members id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.members ALTER COLUMN id SET DEFAULT nextval('public.members_id_seq'::regclass);


--
-- Name: messages message_id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messages ALTER COLUMN message_id SET DEFAULT nextval('public.messages_message_id_seq'::regclass);


--
-- Name: place_events id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.place_events ALTER COLUMN id SET DEFAULT nextval('public.place_events_id_seq'::regclass);


--
-- Name: places_cat id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.places_cat ALTER COLUMN id SET DEFAULT nextval('public.places_cat_id_seq'::regclass);


--
-- Name: public_events id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.public_events ALTER COLUMN id SET DEFAULT nextval('public.public_events_id_seq'::regclass);


--
-- Name: researchable_events id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.researchable_events ALTER COLUMN id SET DEFAULT nextval('public.researchable_events_id_seq'::regclass);


--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: chat_rooms; Type: TABLE DATA; Schema: public; Owner: postgres
--


--
-- Data for Name: messages; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: place_events; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: places_cat; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: public_events; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: researchable_events; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Data for Name: spatial_ref_sys; Type: TABLE DATA; Schema: public; Owner: postgres
--



--
-- Name: categories_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.categories_id_seq', 1, false);


--
-- Name: chat_rooms_chat_room_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.chat_rooms_chat_room_id_seq', 1, false);


--
-- Name: errors_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.errors_id_seq', 11, true);


--
-- Name: events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.events_id_seq', 216, true);


--
-- Name: events_member_details_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.events_member_details_id_seq', 288, true);


--
-- Name: events_requests_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.events_requests_id_seq', 92, true);


--
-- Name: friends_links_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.friends_links_id_seq', 138, true);


--
-- Name: google_places_assoc_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.google_places_assoc_id_seq', 1, false);


--
-- Name: google_places_cat_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.google_places_cat_id_seq', 1, false);


--
-- Name: google_places_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.google_places_id_seq', 1, true);


--
-- Name: hack_attempts_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.hack_attempts_id_seq', 1, false);


--
-- Name: logs_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.logs_id_seq', 177, true);


--
-- Name: members_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.members_id_seq', 368, true);


--
-- Name: messages_message_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.messages_message_id_seq', 1, false);


--
-- Name: place_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.place_events_id_seq', 1, false);


--
-- Name: places_cat_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.places_cat_id_seq', 1, false);


--
-- Name: public_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.public_events_id_seq', 1, false);


--
-- Name: researchable_events_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.researchable_events_id_seq', 1, false);


--
-- Name: categories categories_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pkey PRIMARY KEY (id);


--
-- Name: chat_rooms chat_rooms_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.chat_rooms
    ADD CONSTRAINT chat_rooms_pkey PRIMARY KEY (chat_room_id);


--
-- Name: errors errors_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.errors
    ADD CONSTRAINT errors_pkey PRIMARY KEY (id);


--
-- Name: events_member_details events_member_details_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events_member_details
    ADD CONSTRAINT events_member_details_pkey PRIMARY KEY (id);


--
-- Name: events events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events
    ADD CONSTRAINT events_pkey PRIMARY KEY (id);


--
-- Name: events_requests events_requests_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.events_requests
    ADD CONSTRAINT events_requests_pkey PRIMARY KEY (id);


--
-- Name: friends_links friends_links_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.friends_links
    ADD CONSTRAINT friends_links_pkey PRIMARY KEY (id);


--
-- Name: google_places_assoc google_places_assoc_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.google_places_assoc
    ADD CONSTRAINT google_places_assoc_pkey PRIMARY KEY (id);


--
-- Name: google_places_cat google_places_cat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.google_places_cat
    ADD CONSTRAINT google_places_cat_pkey PRIMARY KEY (id);


--
-- Name: google_places google_places_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.google_places
    ADD CONSTRAINT google_places_pkey PRIMARY KEY (id);


--
-- Name: hack_attempts hack_attempts_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.hack_attempts
    ADD CONSTRAINT hack_attempts_pkey PRIMARY KEY (id);


--
-- Name: logs logs_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.logs
    ADD CONSTRAINT logs_pkey PRIMARY KEY (id);


--
-- Name: members members_fb_user_id_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.members
    ADD CONSTRAINT members_fb_user_id_key UNIQUE (fb_user_id);


--
-- Name: members members_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.members
    ADD CONSTRAINT members_pkey PRIMARY KEY (id);


--
-- Name: messages messages_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.messages
    ADD CONSTRAINT messages_pkey PRIMARY KEY (message_id);


--
-- Name: place_events place_events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.place_events
    ADD CONSTRAINT place_events_pkey PRIMARY KEY (id);


--
-- Name: places_cat places_cat_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.places_cat
    ADD CONSTRAINT places_cat_pkey PRIMARY KEY (id);


--
-- Name: public_events public_events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.public_events
    ADD CONSTRAINT public_events_pkey PRIMARY KEY (id);


--
-- Name: researchable_events researchable_events_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.researchable_events
    ADD CONSTRAINT researchable_events_pkey PRIMARY KEY (id);


--
-- PostgreSQL database dump complete
--

