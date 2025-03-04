--
-- PostgreSQL database dump
--

-- Dumped from database version 15.10 (Debian 15.10-0+deb12u1)
-- Dumped by pg_dump version 15.10 (Debian 15.10-0+deb12u1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', 'public', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.categories (
    id integer NOT NULL,
    name character varying(128) NOT NULL,
    description text,
    added_time timestamp with time zone DEFAULT now() NOT NULL
);


--
-- Name: TABLE categories; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.categories IS 'A table of categories';


--
-- Name: COLUMN categories.description; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.categories.description IS 'Some info about this category';


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


--
-- Name: categories_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.categories_id_seq OWNED BY public.categories.id;


--
-- Name: clicks; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.clicks (
    identifier character(64) NOT NULL,
    proxy_id integer NOT NULL,
    "time" timestamp with time zone DEFAULT now() NOT NULL
);


--
-- Name: TABLE clicks; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.clicks IS 'A gigantic table consisting of unique ip address clicks on every proxy';


--
-- Name: COLUMN clicks.identifier; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.clicks.identifier IS 'A sha256hash consisting of the ip + user-agent';


--
-- Name: comments; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.comments (
    id integer NOT NULL,
    content text NOT NULL,
    identifier text,
    "time" timestamp with time zone DEFAULT now() NOT NULL,
    web_id integer,
    proxy_id integer,
    CONSTRAINT must_be_either_webid_or_proxy_id CHECK (((web_id IS NOT NULL) OR (proxy_id IS NOT NULL)))
);


--
-- Name: COLUMN comments.identifier; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.comments.identifier IS 'The user, the hash(user agent + ip), the something something';


--
-- Name: COLUMN comments.web_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.comments.web_id IS 'Is the comment towards a website';


--
-- Name: COLUMN comments.proxy_id; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.comments.proxy_id IS 'Or is the comment towards a proxy';


--
-- Name: CONSTRAINT must_be_either_webid_or_proxy_id ON comments; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON CONSTRAINT must_be_either_webid_or_proxy_id ON public.comments IS 'Either web_id or proxy_id must be set';


--
-- Name: comments_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: comments_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;


--
-- Name: proxy_list; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.proxy_list (
    id integer NOT NULL,
    web_id integer NOT NULL,
    url text NOT NULL,
    test_path text,
    test_match text,
    ping double precision DEFAULT 99.99 NOT NULL,
    return_code integer DEFAULT 0 NOT NULL,
    up boolean DEFAULT false NOT NULL,
    test_reason text,
    added_time timestamp with time zone DEFAULT now() NOT NULL,
    last_active timestamp with time zone DEFAULT '1970-01-01 01:00:00+01'::timestamp with time zone NOT NULL,
    last_checked timestamp with time zone DEFAULT '1970-01-01 01:00:00+01'::timestamp with time zone NOT NULL,
    official boolean DEFAULT false NOT NULL,
    CONSTRAINT verify_url CHECK ((url ~ '^[a-z](?:[-a-z0-9\+\.])*:(?:\/\/(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&''\(\)\*\+,;=:@])|[\/\?])*)?'::text))
);


--
-- Name: TABLE proxy_list; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.proxy_list IS 'List of the proxies';


--
-- Name: COLUMN proxy_list.test_path; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.test_path IS 'Test path, if set to null it will use the value set by the website table instead';


--
-- Name: COLUMN proxy_list.test_match; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.test_match IS 'Content to match, if set to null it will use the value set by the website table instead';


--
-- Name: COLUMN proxy_list.ping; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.ping IS 'Amount of time it takes to download the webpage in seconds';


--
-- Name: COLUMN proxy_list.return_code; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.return_code IS 'HTTP response code from proxy';


--
-- Name: COLUMN proxy_list.up; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.up IS 'Will fail if return_code != 200 or test_match wasn''t found';


--
-- Name: COLUMN proxy_list.test_reason; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.test_reason IS 'Information how/why is failed or succeeded';


--
-- Name: COLUMN proxy_list.last_active; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.last_active IS 'Last time a check has succeeded';


--
-- Name: COLUMN proxy_list.last_checked; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.last_checked IS 'The last time this proxy was checked if working or not';


--
-- Name: COLUMN proxy_list.official; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.proxy_list.official IS 'Whenever the proxy is an official proxy or not';


--
-- Name: proxy_list_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.proxy_list_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: proxy_list_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.proxy_list_id_seq OWNED BY public.proxy_list.id;


--
-- Name: website; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.website (
    id integer NOT NULL,
    name text NOT NULL,
    url text NOT NULL,
    description text,
    ping double precision DEFAULT 99.99 NOT NULL,
    return_code integer DEFAULT 0 NOT NULL,
    up boolean DEFAULT false NOT NULL,
    test_match text NOT NULL,
    test_path text DEFAULT '/'::text NOT NULL,
    test_reason text,
    added_time timestamp with time zone DEFAULT now() NOT NULL,
    last_active timestamp with time zone DEFAULT '1970-01-01 01:00:00+01'::timestamp with time zone NOT NULL,
    last_checked timestamp with time zone DEFAULT '1970-01-01 01:00:00+01'::timestamp with time zone NOT NULL,
    CONSTRAINT verify_url CHECK ((url ~ '^[a-z](?:[-a-z0-9\+\.])*:(?:\/\/(?:(?:%[0-9a-f][0-9a-f]|[-a-z0-9\._~!\$&''\(\)\*\+,;=:@])|[\/\?])*)?'::text))
);


--
-- Name: TABLE website; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.website IS 'A list of different websites to use';


--
-- Name: COLUMN website.name; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.name IS 'The name of the website';


--
-- Name: COLUMN website.url; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.url IS 'The url of the website';


--
-- Name: COLUMN website.description; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.description IS 'Some description of what the website is';


--
-- Name: COLUMN website.ping; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.ping IS 'The amount of time it takes to load the page in seconds';


--
-- Name: COLUMN website.return_code; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.return_code IS 'The return code from the website';


--
-- Name: COLUMN website.up; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.up IS 'If the website is up or not. This will turn false if return_code != 200 or look_for not found';


--
-- Name: COLUMN website.test_match; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.test_match IS 'A certin string that must be present on the website for it to succeed';


--
-- Name: COLUMN website.test_path; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.test_path IS 'Path to test_match against.';


--
-- Name: COLUMN website.test_reason; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.test_reason IS 'The reason why it failed, or succeeded';


--
-- Name: COLUMN website.last_active; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.last_active IS 'The last time it had a successfull return';


--
-- Name: COLUMN website.last_checked; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN public.website.last_checked IS 'The last time this website was checked if working or not';


--
-- Name: website_categories; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.website_categories (
    cat_id integer NOT NULL,
    web_id integer NOT NULL
);


--
-- Name: TABLE website_categories; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON TABLE public.website_categories IS 'Join table for categories and websites';


--
-- Name: website_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.website_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: website_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.website_id_seq OWNED BY public.website.id;


--
-- Name: categories id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories ALTER COLUMN id SET DEFAULT nextval('public.categories_id_seq'::regclass);


--
-- Name: comments id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);


--
-- Name: proxy_list id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proxy_list ALTER COLUMN id SET DEFAULT nextval('public.proxy_list_id_seq'::regclass);


--
-- Name: website id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.website ALTER COLUMN id SET DEFAULT nextval('public.website_id_seq'::regclass);


--
-- Name: proxy_list proxy_list_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proxy_list
    ADD CONSTRAINT proxy_list_pk PRIMARY KEY (id);


--
-- Name: website website_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.website
    ADD CONSTRAINT website_pk PRIMARY KEY (id);


--
-- Name: front_page; Type: MATERIALIZED VIEW; Schema: public; Owner: postgres
--

CREATE MATERIALIZED VIEW public.front_page AS
 SELECT w.id,
    w.name,
    w.description,
    w.url,
    w.ping,
    w.return_code,
        CASE
            WHEN ((w.return_code = 403) OR (w.return_code = 401) OR (w.return_code = 429)) THEN true
            ELSE false
        END AS is_restricted,
    w.up,
    w.test_reason,
    w.added_time,
    w.last_active,
    w.last_checked,
    COALESCE(cats.names, '[]'::jsonb) AS categories,
    count(c.proxy_id) AS clicks,
    count(
        CASE
            WHEN (c."time" >= (now() - '24:00:00'::interval)) THEN c.proxy_id
            ELSE NULL::integer
        END) AS clicks_last_24h,
    COALESCE(pl.proxy_list, '[]'::jsonb) AS proxy_list,
    COALESCE(cmt.comments, '[]'::jsonb) AS comments
   FROM (((((public.website w
     LEFT JOIN ( SELECT jsonb_agg(cat.name) AS names,
            wc.web_id
           FROM (public.categories cat
             JOIN public.website_categories wc ON ((cat.id = wc.cat_id)))
          GROUP BY wc.web_id) cats ON ((cats.web_id = w.id)))
     LEFT JOIN public.proxy_list pl2 ON ((pl2.web_id = w.id)))
     LEFT JOIN public.clicks c ON ((c.proxy_id = pl2.id)))
     LEFT JOIN ( SELECT c_1.web_id,
            jsonb_agg(row_to_json(c_1.*)) AS comments
           FROM ( SELECT comments.id,
                    comments.content,
                    comments.identifier,
                    comments."time",
                    comments.web_id,
                    comments.proxy_id
                   FROM public.comments
                  ORDER BY comments."time" DESC
                 LIMIT 5) c_1
          GROUP BY c_1.web_id) cmt ON ((cmt.web_id = w.id)))
     LEFT JOIN ( SELECT pl2_1.web_id,
            jsonb_agg(row_to_json(pl2_1.*) ORDER BY pl2_1.clicks_last_24h DESC) AS proxy_list
           FROM ( SELECT t.id,
                    t.web_id,
                    t.url,
                    t.official,
                    t.ping,
                    t.return_code,
                    t.is_restricted,
                    t.up,
                    t.test_reason,
                    t.added_time,
                    t.last_active,
                    t.last_checked,
                    t.clicks,
                    t.clicks_last_24h,
                    t.rn
                   FROM ( SELECT pl3.id,
                            pl3.web_id,
                            pl3.url,
                            pl3.official,
                            pl3.ping,
                            pl3.return_code,
                                CASE
                                    WHEN ((pl3.return_code = 403) OR (pl3.return_code = 401) OR (pl3.return_code = 429)) THEN true
                                    ELSE false
                                END AS is_restricted,
                            pl3.up,
                            pl3.test_reason,
                            pl3.added_time,
                            pl3.last_active,
                            pl3.last_checked,
                            count(c_1.proxy_id) AS clicks,
                            count(
                                CASE
                                    WHEN (c_1."time" >= (now() - '24:00:00'::interval)) THEN c_1.proxy_id
                                    ELSE NULL::integer
                                END) AS clicks_last_24h,
                            row_number() OVER (PARTITION BY pl3.web_id ORDER BY (count(
                                CASE
                                    WHEN (c_1."time" >= (now() - '24:00:00'::interval)) THEN c_1.proxy_id
                                    ELSE NULL::integer
                                END)) DESC) AS rn
                           FROM (public.proxy_list pl3
                             LEFT JOIN public.clicks c_1 ON ((pl3.id = c_1.proxy_id)))
                          WHERE ((pl3.up = true) OR (pl3.return_code = 403) OR (pl3.return_code = 401) OR (pl3.return_code = 429))
                          GROUP BY pl3.id) t
                  WHERE (t.rn <= 5)) pl2_1
          GROUP BY pl2_1.web_id) pl ON ((pl.web_id = w.id)))
  GROUP BY w.id, cats.names, pl.proxy_list, cmt.comments
  ORDER BY (count(c.proxy_id)) DESC
  WITH NO DATA;


--
-- Name: website_info; Type: MATERIALIZED VIEW; Schema: public; Owner: postgres
--

CREATE MATERIALIZED VIEW public.website_info AS
 SELECT pl.id,
    pl.web_id,
    pl.url,
    pl.official,
    pl.ping,
    pl.return_code,
        CASE
            WHEN ((pl.return_code = 403) OR (pl.return_code = 401) OR (pl.return_code = 429)) THEN true
            ELSE false
        END AS is_restricted,
    pl.up,
    pl.test_reason,
    pl.added_time,
    pl.last_active,
    pl.last_checked,
    COALESCE(c2.comments, '[]'::jsonb) AS comments,
    count(c.proxy_id) AS clicks,
    count(
        CASE
            WHEN (c."time" >= (now() - '24:00:00'::interval)) THEN c.proxy_id
            ELSE NULL::integer
        END) AS clicks_last_24h
   FROM ((public.proxy_list pl
     LEFT JOIN public.clicks c ON ((c.proxy_id = pl.id)))
     LEFT JOIN ( SELECT comments.proxy_id,
            jsonb_agg(comments.*) AS comments
           FROM public.comments
          GROUP BY comments.proxy_id) c2 ON ((c2.proxy_id = pl.id)))
  GROUP BY pl.id, c2.comments
  ORDER BY (count(
        CASE
            WHEN (c."time" >= (now() - '24:00:00'::interval)) THEN c.proxy_id
            ELSE NULL::integer
        END)) DESC, (count(c.proxy_id)) DESC
  WITH NO DATA;



--
-- Name: categories categories_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.categories
    ADD CONSTRAINT categories_pk PRIMARY KEY (id);


--
-- Name: clicks clicks_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.clicks
    ADD CONSTRAINT clicks_pk PRIMARY KEY (identifier, proxy_id);


--
-- Name: comments comments_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pk PRIMARY KEY (id);


--
-- Name: website_categories website_categories_pk; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.website_categories
    ADD CONSTRAINT website_categories_pk PRIMARY KEY (web_id, cat_id);


--
-- Name: categories_name_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX categories_name_uindex ON public.categories USING btree (name);


--
-- Name: fp_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX fp_id ON public.front_page USING btree (id);


--
-- Name: proxy_list_url_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX proxy_list_url_uindex ON public.proxy_list USING btree (url);


--
-- Name: website_url_uindex; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX website_url_uindex ON public.website USING btree (url);


--
-- Name: wi_id; Type: INDEX; Schema: public; Owner: postgres
--

CREATE UNIQUE INDEX wi_id ON public.website_info USING btree (id);


--
-- Name: clicks clicks_proxy_list_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.clicks
    ADD CONSTRAINT clicks_proxy_list_id_fk FOREIGN KEY (proxy_id) REFERENCES public.proxy_list(id) ON DELETE CASCADE;


--
-- Name: proxy_list proxy_list_website_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.proxy_list
    ADD CONSTRAINT proxy_list_website_id_fk FOREIGN KEY (web_id) REFERENCES public.website(id) ON DELETE CASCADE;


--
-- Name: website_categories website_categories_categories_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.website_categories
    ADD CONSTRAINT website_categories_categories_id_fk FOREIGN KEY (cat_id) REFERENCES public.categories(id) ON DELETE CASCADE;


--
-- Name: website_categories website_categories_website_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.website_categories
    ADD CONSTRAINT website_categories_website_id_fk FOREIGN KEY (web_id) REFERENCES public.website(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

