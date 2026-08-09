DO $$
BEGIN
    IF EXISTS (SELECT 1 FROM public.website LIMIT 1) THEN
        RAISE NOTICE 'Database already seeded, skipping.';
        RETURN;
    END IF;
END $$;

--
-- PostgreSQL database dump
--



--
-- Data for Name: categories; Type: TABLE DATA; Schema: public; Owner: knabeninfo
--

COPY public.categories (id, name, description, added_time) FROM stdin;
1	anime	Anime is hand-drawn and computer-generated animation originating from Japan. Outside Japan and in English, anime refers specifically to animation produced in Japan. However, in Japan and in Japanese, anime describes all animated works, regardless of style or origin.	2023-07-14 14:39:48.903773+00
2	movies	It's movies, not TV-Series. 1 hour+ length typically yes.	2023-07-15 19:52:23.122701+00
3	tv-shows	TV-Shows, typically 10+ episodes of shorter 40 minutes videos	2023-07-15 19:52:23.122701+00
4	music	Ding!	2023-07-15 19:52:23.122701+00
5	lossless	High quality Dings!	2023-07-15 19:52:23.122701+00
6	pc	software, games, things for your PC	2023-07-15 19:52:23.122701+00
7	games	A program, with a challange	2023-07-15 19:52:23.122701+00
8	console	Is consoles even relevant nowadays? Let me know down below in the comment section, and don't forget to like and subscribe	2023-07-15 19:52:23.122701+00
9	e-books	Electronic books. Might want to consider checking Anna's archive instead: https://annas-archive.org	2023-07-15 19:52:23.122701+00
10	audio books	Books, except you don't need to read it	2023-07-15 19:52:23.122701+00
11	software	software, applications. Executable things on your operating systems	2023-07-15 19:52:23.122701+00
12	unix	Arch BTW	2023-07-15 19:52:23.122701+00
13	mac	Apple stuff	2023-07-15 19:52:23.122701+00
14	documentaries	Factual scientific historical serious videos	2023-07-15 19:57:46.538174+00
15	spanish	hola gente española :3 Esta es la categoría española.	2023-07-15 20:06:34.222014+00
16	japanese	これは日本語ですよ	2023-07-15 20:06:34.222014+00
17	russian	Это русская категория. Я надеюсь, что мы все скоро обретем мир	2023-07-15 20:10:42.603293+00
18	private	Usually requires an invite from someone, or else you are locked out	2023-07-19 09:41:30.718575+00
19	semi-private	Semi-private, meaning you can view a bit of the website, but are limited and needs to make an account to get the full experience	2023-07-19 11:51:44.74035+00
20	porn	The thing that adults do to keep evolution going forward	2023-07-24 13:23:21.613746+00
22	foreign	Something that's not English pretty much	2023-07-24 13:35:08.553198+00
23	pictures	An image, photo, static 2D images	2023-07-24 13:36:13.0599+00
21	4k	4x 1080p, it's sharp it's nice	2023-07-24 13:31:24.976851+00
24	sports	Breathing noises	2023-07-24 14:06:47.165426+00
26	manga	More manga is produced every day than toilet paper	2023-07-24 14:27:09.5552+00
27	doujinshi	Erotic manga	2023-07-24 14:27:09.5552+00
\.


--
-- Data for Name: website; Type: TABLE DATA; Schema: public; Owner: knabeninfo
--

COPY public.website (id, name, url, description, ping, return_code, up, test_match, test_path, test_reason, added_time, last_active, last_checked) FROM stdin;
4	Nyaa.si	https://nyaa.si	A BitTorrent community focused on Eastern Asian media including anime, manga, music, and more	0.24563384056091309	200	t	<a href="magnet:?xt=urn:btih:	/	\N	2023-07-14 15:20:10.110615+00	2026-08-09 20:24:58+00	2026-08-09 20:26:19+00
8	RuTracker	https://rutracker.org	Rutracker.org (torrents.ru until 2010) is the biggest Russian BitTorrent tracker. As of March 2022, it had 13.74 million registered users, 2.141 million of torrents (1.975 million of them were active), and the total volume of all torrents was 4.653 Petabytes.	0.2115640640258789	200	t	viewtopic.php?t=	/forum/index.php	\N	2023-07-15 19:39:40.108928+00	2026-08-09 20:25:27+00	2026-08-09 20:26:19+00
12	TorrentGalaxy	https://torrentgalaxy.to	TorrentGalaxy is a relatively new torrent site, which launched little over two years ago. The site has a dedicated group of uploaders and an active community. In addition to torrents, TorrentGalaxy also makes some releases available for streaming.	0.019113779067993164	0	f	<a href="/torrent/	/latest	HTTPSConnectionPool(host='torrentgalaxy.to', port=443): Max retries exceeded with url: /latest (Caused by NameResolutionError("HTTPSConnection(host='torrentgalaxy.to', port=443): Failed to resolve 'torrentgalaxy.to' ([Errno -2] Name or service not known)"))	2023-07-16 21:32:37.817942+00	2025-02-27 00:40:38+00	2026-08-09 20:26:19+00
15	LimeTorrents	https://www.limetorrents.fun	Limetorrents is a torrent search engine and directory which do NOT host torrent files but links to torrents hosted on other websites. Limetorrents tries to be family-safe, so adult submissions or search are not allowed. Download Verified Popular Torrents Movies, TV Shows, Games, Music, Anime and Software, Bittorrent Downloading Absolutely for free	0.11396455764770508	200	t	-torrent-	/latest100	\N	2023-07-24 10:24:24.248755+00	2026-08-09 20:26:04+00	2026-08-09 20:26:19+00
16	Sukebei.Nyaa.si	https://sukebei.nyaa.si	A BitTorrent community focused on Eastern Asian media including anime, manga, music, and more. Unlike Nyaa.si, Sukebei.Nyaa.si has porn and hentai	11.021287679672241	0	f	<a href="magnet:?xt=urn:btih:	/	Timeout 11.0s	2023-07-24 13:54:52.084395+00	2026-05-21 04:06:39+00	2026-08-09 20:26:19+00
17	Solid Torrents	https://solidtorrents.to	Solid Torrents aims to be the “most user friendly torrent search engine”. The sites search results page offers direct links to cached torrent files as well as magnet links	0.7981288433074951	200	t	<a href="magnet:?xt	/search?q=test	\N	2024-11-10 20:51:14.071622+00	2026-08-09 20:26:19+00	2026-08-09 20:26:19+00
9	Torrent Downloads	https://www.torrentdownloads.pro	Torrent Downloads, Search and Download free Movies, TV shows, Music, PC/PS2/PSP/Wii/Xbox Games torrents from our Bittorrent database.	0.3637821674346924	200	t	<a href="/torrent/	/	\N	2023-07-15 19:39:40.108928+00	2026-08-09 20:25:28+00	2026-08-09 20:26:19+00
11	IPTorrents	https://iptorrents.com	IPTorrents is a private tracker, and you have to register before you can get full access to the site. Before you do anything here at IPTorrents.com : A Place Where Speed Speaks!	0.27291417121887207	200	t	a	/	\N	2023-07-15 19:39:40.108928+00	2026-08-09 20:25:43+00	2026-08-09 20:26:19+00
3	The Pirate Bay	https://thepiratebay.org	The Pirate Bay is an online index of digital content of entertainment media and software. Founded in 2003 by Swedish think tank Piratbyrån. The Pirate Bay allows visitors to search, download, and contribute magnet links and torrent files, which facilitate peer-to-peer, file sharing among users of the BitTorrent protocol.	0.05074763298034668	200	t	"info_hash":"	https://apibay.org/precompiled/data_top100_recent.json	\N	2023-07-14 14:39:14.800135+00	2026-08-09 20:24:49+00	2026-08-09 20:26:19+00
13	FitGirl Repacks	https://fitgirl-repacks.site	FitGirl Repacks is a website distributing pirated video games. FitGirl Repacks is known for "repacking" games - compressing them significantly so they can be downloaded and shared more efficiently. It is by no means a traditional torrent site, it is the home of a popular 'girl' who releases slimmed-down cracked versions of popular games, which keeps download times to a minimum.	0.6458725929260254	200	t	<a href="magnet:?xt=urn:btih:	/	\N	2023-07-24 10:12:47.471463+00	2026-08-09 20:26:02+00	2026-08-09 20:26:19+00
5	1337x	https://1337x.to	1337x is a website that provides a directory of torrent files and magnet links used for peer-to-peer file sharing through the BitTorrent protocol. 1337x is the second most popular torrent website as of 2023.	0.08355498313903809	403	f	<a href="/torrent/	/home/	Return code not satisfied: 403	2023-07-15 19:39:40.108928+00	2025-11-28 04:50:25+00	2026-08-09 20:26:19+00
7	EZTV	https://eztvx.to	EZTV was a TV torrent distribution group founded in May 2005 and dissolved in April 2015, after a hostile takeover of their domains and brand by "EZCLOUD LIMITED". It quickly became the most visited torrent site for TV shows.	0.14771819114685059	403	f	<a href="/ep/	/home	Return code not satisfied: 403	2023-07-15 19:39:40.108928+00	2026-03-13 02:11:58+00	2026-08-09 20:26:19+00
6	Cine Calidad	https://wv.cinecalidad.gg	La pagina #1 de Cinecalidad para ver películas y descargarlas a tu pc o celular gratis, con toda la información disponible, hd y Ultra 4K en 1 Link.	0.22662687301635742	0	f	 href="https://www.cinecalidad.men/ver-	/	HTTPSConnectionPool(host='wv.cinecalidad.gg', port=443): Max retries exceeded with url: / (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-15 19:39:40.108928+00	2023-08-10 06:58:26+00	2026-08-09 20:26:19+00
10	YTS	https://yts.bz	Download free yify movies torrents in 720p, 1080p and 3D quality. The fastest downloads at the smallest size.	0.3633763790130615	403	f	browse-movie-bottom	/browse-movies	Return code not satisfied: 403	2023-07-15 19:39:40.108928+00	2026-04-27 05:54:00+00	2026-08-09 20:26:19+00
\.




--
-- Data for Name: proxy_list; Type: TABLE DATA; Schema: public; Owner: knabeninfo
--

COPY public.proxy_list (id, web_id, url, test_path, test_match, ping, return_code, up, test_reason, added_time, last_active, last_checked, official) FROM stdin;
106	10	https://yts.gg	\N	\N	0.08245062828063965	403	f	Return code not satisfied: 403	2026-03-28 00:23:12.628106+00	2026-04-27 05:54:07+00	1970-01-01 00:00:00+00	t
92	3	https://thepiratebayx.org	/search/	<a href="magnet:?xt=urn:btih:	0.7123525142669678	0	f	HTTPConnectionPool(host='click-v4.junclikrmedi.com', port=80): Max retries exceeded with url: /click?i=EbdFcI4W1*I_0 (Caused by NameResolutionError("HTTPConnection(host='click-v4.junclikrmedi.com', port=80): Failed to resolve 'click-v4.junclikrmedi.com' ([Errno -2] Name or service not known)"))	2023-12-03 09:01:27.452522+00	2024-08-09 11:55:52+00	1970-01-01 00:00:00+00	f
1	4	https://nyaa.ink	\N	\N	0.31028175354003906	200	t	\N	2023-07-14 15:21:14.00409+00	2026-08-09 20:24:58+00	1970-01-01 00:00:00+00	f
19	11	https://ipt.cool	\N	\N	0.2626495361328125	200	t	\N	2023-07-19 09:23:24.653169+00	2026-08-09 20:25:43+00	1970-01-01 00:00:00+00	t
100	12	https://torrentgalaxy.one	/	href="/post-detail/	0.3012847900390625	200	t	\N	2024-11-25 00:31:47.688372+00	2026-08-09 20:26:01+00	1970-01-01 00:00:00+00	f
14	4	https://ny.iss.one	\N	\N	0.28511476516723633	200	t	\N	2023-07-19 00:12:38.696852+00	2026-08-09 20:25:01+00	1970-01-01 00:00:00+00	f
46	3	https://pbays.top	/recent	<a href="magnet:?xt=urn:btih:	0.7072765827178955	404	f	Return code not satisfied: 404	2023-07-23 09:15:57.548252+00	2023-12-20 14:09:37+00	1970-01-01 00:00:00+00	f
5	5	https://1337x.st	\N	\N	0.10131144523620605	403	f	Return code not satisfied: 403	2023-07-16 12:13:45.829671+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
104	12	https://torrentgalaxy.info	/	href="/post-detail/	0.3338015079498291	200	t	\N	2026-03-25 19:42:05.478888+00	2026-08-09 20:26:01+00	1970-01-01 00:00:00+00	f
105	12	https://torrentgalaxy.space	/	href="/post-detail/	0.4848623275756836	0	f	HTTPSConnectionPool(host='torrentgalaxy.space', port=443): Max retries exceeded with url: / (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2026-03-25 19:42:05.478888+00	2026-05-10 08:23:03+00	1970-01-01 00:00:00+00	f
49	3	https://www.thepiratebay2.to	/recent	<a href="magnet:?xt=urn:btih:	0.37778496742248535	404	f	Return code not satisfied: 404	2023-07-23 09:15:57.548252+00	2025-09-18 01:55:14+00	1970-01-01 00:00:00+00	f
50	3	https://www1.thepiratebay3.to	/recent	<a href="magnet:?xt=urn:btih:	0.18839383125305176	403	f	Return code not satisfied: 403	2023-07-23 09:15:57.548252+00	2026-07-30 14:11:30+00	1970-01-01 00:00:00+00	f
51	3	https://tpb.re	/api.php?url=/precompiled/data_top100_recent.json	"info_hash":"	0.9123885631561279	200	t	\N	2023-07-23 09:15:57.548252+00	2026-08-09 20:24:53+00	1970-01-01 00:00:00+00	f
77	13	https://fitgirl-repacks.uproxy.cc	\N	\N	0.06327128410339355	0	f	HTTPSConnectionPool(host='fitgirl-repacks.uproxy.cc', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='fitgirl-repacks.uproxy.cc', port=443): Failed to resolve 'fitgirl-repacks.uproxy.cc' ([Errno -2] Name or service not known)"))	2023-07-24 10:14:43.189592+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
57	5	https://1337x.torrentbay.net	\N	\N	0.49213099479675293	404	f	Return code not satisfied: 404	2023-07-24 06:17:44.092808+00	2023-10-24 17:13:07+00	1970-01-01 00:00:00+00	f
101	15	https://limetor.pro	\N	\N	1.4238014221191406	200	f	Test match not found	2025-10-30 16:07:18.901891+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
84	16	https://sukebei.nyaa.sbs	\N	\N	0.053905487060546875	0	f	HTTPSConnectionPool(host='sukebei.nyaa.sbs', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='sukebei.nyaa.sbs', port=443): Failed to resolve 'sukebei.nyaa.sbs' ([Errno -2] Name or service not known)"))	2023-07-24 14:00:19.541236+00	2023-08-06 17:41:38+00	1970-01-01 00:00:00+00	f
87	3	https://tpb.latestcache.com	/search/	<a href="magnet:?xt=urn:btih:	0.511744499206543	0	f	HTTPSConnectionPool(host='tpb.latestcache.com', port=443): Max retries exceeded with url: /search/ (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-12-03 09:01:27.452522+00	2024-02-14 20:11:59+00	1970-01-01 00:00:00+00	f
58	5	https://1337x.proxybit.pics	\N	\N	11.297159433364868	0	f	Timeout 11.0s	2023-07-24 06:17:44.092808+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
59	5	https://1337x.unblockproject.cfd	\N	\N	0.08345389366149902	0	f	HTTPSConnectionPool(host='1337x.unblockproject.cfd', port=443): Max retries exceeded with url: /home/ (Caused by NameResolutionError("HTTPSConnection(host='1337x.unblockproject.cfd', port=443): Failed to resolve '1337x.unblockproject.cfd' ([Errno -2] Name or service not known)"))	2023-07-24 06:17:44.092808+00	2023-07-26 17:07:00+00	1970-01-01 00:00:00+00	f
63	5	https://1337x.ninjaproxy1.com	\N	\N	0.1315314769744873	403	f	Return code not satisfied: 403	2023-07-24 06:41:52.091386+00	2024-07-12 17:06:15+00	1970-01-01 00:00:00+00	f
103	5	https://x1337x.cc	\N	\N	0.12807655334472656	403	f	Return code not satisfied: 403	2026-02-07 08:44:07.153963+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
38	9	https://torrentdownloads.g3g.ink	\N	\N	0.07236838340759277	0	f	HTTPSConnectionPool(host='torrentdownloads.g3g.ink', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='torrentdownloads.g3g.ink', port=443): Failed to resolve 'torrentdownloads.g3g.ink' ([Errno -2] Name or service not known)"))	2023-07-19 10:19:50.170156+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
23	11	https://ip.getcrazy.me	\N	\N	0.21709632873535156	403	f	Return code not satisfied: 403	2023-07-19 09:23:24.653169+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
16	4	https://nyaa.unblockproject.art	\N	\N	1.7374553680419922	200	f	Test match not found	2023-07-19 00:14:52.090649+00	2023-07-26 17:06:54+00	1970-01-01 00:00:00+00	f
27	5	https://x1337x.ws	\N	\N	0.1265096664428711	403	f	Return code not satisfied: 403	2023-07-19 09:56:42.780271+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
85	6	https://cinecalidad.im	\N	 href="/pelicula/	0.09604525566101074	0	f	HTTPSConnectionPool(host='cinecalidad.im', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='cinecalidad.im', port=443): Failed to resolve 'cinecalidad.im' ([Errno -2] Name or service not known)"))	2023-08-09 23:16:13.782848+00	2024-06-21 22:57:02+00	1970-01-01 00:00:00+00	f
102	15	https://limetorrents.asia	\N	\N	0.7714023590087891	200	f	Test match not found	2025-10-30 16:07:18.901891+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
83	16	https://sukebei-nyaa-si.translate.goog/?_x_tr_sl=es&_x_tr_tl=en&_x_tr_hl=en	\N	\N	0.20399022102355957	400	f	Return code not satisfied: 400	2023-07-24 14:00:19.541236+00	2023-09-07 08:20:47+00	1970-01-01 00:00:00+00	f
86	6	https://cinecalidad.ma	\N	 href="https://cinecalidad.ma/archivos/	1.5299952030181885	0	f	HTTPSConnectionPool(host='cinecalidad.ma', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='cinecalidad.ma', port=443): Failed to resolve 'cinecalidad.ma' ([Errno -2] Name or service not known)"))	2023-08-09 23:19:36.906409+00	2024-05-17 19:26:34+00	1970-01-01 00:00:00+00	t
33	7	https://eztv.tf	\N	\N	0.1369037628173828	403	f	Return code not satisfied: 403	2023-07-19 10:16:08.261181+00	2026-03-24 08:53:52+00	1970-01-01 00:00:00+00	f
62	3	https://thepiratebay.unblockninja.com	/api/precompiled/data_top100_recent.json	"info_hash":"	0.0318605899810791	0	f	HTTPSConnectionPool(host='thepiratebay.unblockninja.com', port=443): Max retries exceeded with url: /api/precompiled/data_top100_recent.json (Caused by NameResolutionError("HTTPSConnection(host='thepiratebay.unblockninja.com', port=443): Failed to resolve 'thepiratebay.unblockninja.com' ([Errno -2] Name or service not known)"))	2023-07-24 06:41:00.048837+00	2026-03-25 09:19:47+00	1970-01-01 00:00:00+00	f
11	4	https://nyaa.unblockninja.com	\N	\N	0.027193069458007812	0	f	HTTPSConnectionPool(host='nyaa.unblockninja.com', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='nyaa.unblockninja.com', port=443): Failed to resolve 'nyaa.unblockninja.com' ([Errno -2] Name or service not known)"))	2023-07-19 00:10:06.367539+00	2026-03-25 05:15:55+00	1970-01-01 00:00:00+00	f
35	7	https://eztv.unblockninja.com	\N	\N	0.04704713821411133	0	f	HTTPSConnectionPool(host='eztv.unblockninja.com', port=443): Max retries exceeded with url: /home (Caused by NameResolutionError("HTTPSConnection(host='eztv.unblockninja.com', port=443): Failed to resolve 'eztv.unblockninja.com' ([Errno -2] Name or service not known)"))	2023-07-19 10:16:08.261181+00	2026-03-25 03:14:04+00	1970-01-01 00:00:00+00	f
70	7	https://eztv.torrentsbay.org	\N	\N	0.35399794578552246	403	f	Return code not satisfied: 403	2023-07-24 06:49:17.0475+00	2025-04-11 12:19:02+00	1970-01-01 00:00:00+00	f
72	7	https://eztv.yt	\N	\N	0.3053553104400635	403	f	Return code not satisfied: 403	2023-07-24 06:49:17.0475+00	2026-03-24 08:53:56+00	1970-01-01 00:00:00+00	f
41	8	https://rutracker.net	\N	\N	0.10109877586364746	403	f	Return code not satisfied: 403	2023-07-19 10:41:04.020578+00	2026-07-27 15:11:32+00	1970-01-01 00:00:00+00	f
39	9	https://torrentdownloads.mrunblock.life	\N	\N	1.3155879974365234	200	f	Test match not found	2023-07-19 10:19:50.170156+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
67	9	https://torrentdownloads.unblockninja.com	\N	\N	0.028052330017089844	0	f	HTTPSConnectionPool(host='torrentdownloads.unblockninja.com', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='torrentdownloads.unblockninja.com', port=443): Failed to resolve 'torrentdownloads.unblockninja.com' ([Errno -2] Name or service not known)"))	2023-07-24 06:45:48.348441+00	2026-03-25 13:25:00+00	1970-01-01 00:00:00+00	f
60	10	https://www.yts.homes	\N	\N	0.519399881362915	0	f	HTTPSConnectionPool(host='www.yts.homes', port=443): Max retries exceeded with url: /browse-movies (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-24 06:28:33.243418+00	2024-12-11 20:38:31+00	1970-01-01 00:00:00+00	f
36	7	https://eztv.unblockit.rsvp	\N	\N	0.5022099018096924	0	f	HTTPSConnectionPool(host='eztv.unblockit.rsvp', port=443): Max retries exceeded with url: /home (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-19 10:16:08.261181+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
80	15	https://www.limetorrentx.cc	\N	\N	0.499997615814209	0	f	HTTPSConnectionPool(host='www.limetorrentx.cc', port=443): Max retries exceeded with url: /latest100 (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-24 10:30:44.079216+00	2025-03-04 11:09:03+00	1970-01-01 00:00:00+00	f
90	3	https://archivebay.online	/search/	<a href="magnet:?xt=urn:btih:	0.04232931137084961	0	f	HTTPSConnectionPool(host='archivebay.online', port=443): Max retries exceeded with url: /search/ (Caused by NameResolutionError("HTTPSConnection(host='archivebay.online', port=443): Failed to resolve 'archivebay.online' ([Errno -2] Name or service not known)"))	2023-12-03 09:01:27.452522+00	2024-01-23 18:32:25+00	1970-01-01 00:00:00+00	f
2	3	https://unblocked.knaben.info/thepiratebay	/recent	<a href="magnet:?xt=urn:btih:	0.13739752769470215	403	f	Return code not satisfied: 403	2023-07-15 10:28:35.474263+00	2025-12-23 10:13:50+00	1970-01-01 00:00:00+00	f
3	3	https://thepiratebay10.org	/recent	<a href="magnet:?xt=urn:btih:	0.2951788902282715	200	t	\N	2023-07-15 10:48:47.616639+00	2026-08-09 20:24:50+00	1970-01-01 00:00:00+00	f
30	10	https://yts.pm	\N	\N	0.01944565773010254	0	f	HTTPSConnectionPool(host='yts.pm', port=443): Max retries exceeded with url: /browse-movies (Caused by NameResolutionError("HTTPSConnection(host='yts.pm', port=443): Failed to resolve 'yts.pm' ([Errno -2] Name or service not known)"))	2023-07-19 10:03:15.299548+00	2023-10-11 08:17:51+00	1970-01-01 00:00:00+00	f
31	10	https://yts.unblockit.rsvp	\N	\N	0.6111948490142822	0	f	HTTPSConnectionPool(host='yts.unblockit.rsvp', port=443): Max retries exceeded with url: /browse-movies (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-19 10:03:15.299548+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
52	3	https://thebay.cf	/api.php?url=/precompiled/data_top100_recent.json	"info_hash":"	0.30560994148254395	0	f	HTTPSConnectionPool(host='thebay.cf', port=443): Max retries exceeded with url: /api.php?url=/precompiled/data_top100_recent.json (Caused by NameResolutionError("HTTPSConnection(host='thebay.cf', port=443): Failed to resolve 'thebay.cf' ([Errno -2] Name or service not known)"))	2023-07-23 09:15:57.548252+00	2024-02-09 18:57:39+00	1970-01-01 00:00:00+00	f
53	3	https://piratebay.pro	/search/	<a href="magnet:?xt=urn:btih:	0.4502565860748291	0	f	HTTPSConnectionPool(host='piratebay.pro', port=443): Max retries exceeded with url: /search/ (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-23 09:15:57.548252+00	2025-01-17 01:37:31+00	1970-01-01 00:00:00+00	f
91	3	https://themirror.wiki	/search/	<a href="magnet:?xt=urn:btih:	1.1662416458129883	200	f	Test match not found	2023-12-03 09:01:27.452522+00	2024-01-19 23:31:32+00	1970-01-01 00:00:00+00	f
32	10	https://yts.rs	\N	"url": "https://yts.rs/	11.15027141571045	0	f	Timeout 11.0s	2023-07-19 10:03:15.299548+00	2026-07-26 04:45:14+00	1970-01-01 00:00:00+00	f
10	4	https://nyaa.mrunblock.life	\N	\N	1.6727099418640137	200	f	Test match not found	2023-07-19 00:10:06.367539+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
20	11	https://ipt.world	\N	\N	0.5436580181121826	200	t	\N	2023-07-19 09:23:24.653169+00	2026-08-09 20:25:44+00	1970-01-01 00:00:00+00	t
18	4	https://nyaa.sbs	\N	\N	0.05508255958557129	0	f	HTTPSConnectionPool(host='nyaa.sbs', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='nyaa.sbs', port=443): Failed to resolve 'nyaa.sbs' ([Errno -2] Name or service not known)"))	2023-07-19 00:14:52.090649+00	2023-08-06 17:40:36+00	1970-01-01 00:00:00+00	f
26	11	https://iptorrents.octopus.town	\N	\N	0.29601597785949707	200	t	\N	2023-07-19 09:24:37.1544+00	2026-08-09 20:25:49+00	1970-01-01 00:00:00+00	f
29	5	https://x1337x.se	\N	\N	0.027911901473999023	0	f	HTTPSConnectionPool(host='x1337x.se', port=443): Max retries exceeded with url: /home/ (Caused by NameResolutionError("HTTPSConnection(host='x1337x.se', port=443): Failed to resolve 'x1337x.se' ([Errno -2] Name or service not known)"))	2023-07-19 09:56:42.780271+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
56	5	https://1337x.unblockit.rsvp	\N	\N	0.5848314762115479	0	f	HTTPSConnectionPool(host='1337x.unblockit.rsvp', port=443): Max retries exceeded with url: /home/ (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-24 06:17:44.092808+00	2024-07-03 04:29:46+00	1970-01-01 00:00:00+00	f
76	13	https://fitgirl-repacks.piracyproxy.dev	\N	\N	0.10970592498779297	0	f	HTTPSConnectionPool(host='fitgirl-repacks.piracyproxy.dev', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='fitgirl-repacks.piracyproxy.dev', port=443): Failed to resolve 'fitgirl-repacks.piracyproxy.dev' ([Errno -2] Name or service not known)"))	2023-07-24 10:14:43.189592+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
9	4	https://nyaa.iss.ink	\N	\N	0.15795230865478516	200	t	\N	2023-07-19 00:08:36.900401+00	2026-08-09 20:24:59+00	1970-01-01 00:00:00+00	f
12	4	https://nyaa.nocensor.click	\N	\N	0.0602107048034668	0	f	HTTPSConnectionPool(host='nyaa.nocensor.click', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='nyaa.nocensor.click', port=443): Failed to resolve 'nyaa.nocensor.click' ([Errno -2] Name or service not known)"))	2023-07-19 00:10:06.367539+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
13	4	https://nyaa.land	\N	\N	0.1370227336883545	200	t	\N	2023-07-19 00:10:06.367539+00	2026-08-09 20:25:00+00	1970-01-01 00:00:00+00	f
15	4	https://nyaa.123unblock.mom	\N	\N	1.0191256999969482	200	f	Test match not found	2023-07-19 00:12:38.696852+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
17	4	https://nyaa-si.translate.goog/?_x_tr_sl=es&_x_tr_tl=en&_x_tr_hl=en	\N	\N	1.19942045211792	200	t	\N	2023-07-19 00:14:52.090649+00	2026-08-09 20:25:05+00	1970-01-01 00:00:00+00	f
47	3	https://pbays.online	/recent	<a href="magnet:?xt=urn:btih:	0.08400225639343262	0	f	HTTPSConnectionPool(host='pbays.online', port=443): Max retries exceeded with url: /recent (Caused by NameResolutionError("HTTPSConnection(host='pbays.online', port=443): Failed to resolve 'pbays.online' ([Errno -2] Name or service not known)"))	2023-07-23 09:15:57.548252+00	2023-12-20 14:09:37+00	1970-01-01 00:00:00+00	f
54	3	https://mirrorbay.org	/search/	<a href="magnet:?xt=urn:btih:	0.26626038551330566	200	t	\N	2023-07-23 09:15:57.548252+00	2026-08-09 20:24:54+00	1970-01-01 00:00:00+00	f
88	3	https://tpb.nowcache.com	/search/	<a href="magnet:?xt=urn:btih:	0.6727285385131836	200	f	Test match not found	2023-12-03 09:01:27.452522+00	2024-02-14 20:12:00+00	1970-01-01 00:00:00+00	f
89	3	https://tpb.clonehub.org	/search/	<a href="magnet:?xt=urn:btih:	0.4496922492980957	0	f	HTTPSConnectionPool(host='tpb.clonehub.org', port=443): Max retries exceeded with url: /search/ (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-12-03 09:01:27.452522+00	2024-01-15 12:24:33+00	1970-01-01 00:00:00+00	f
98	3	https://t-pb.org	/search/	<a href="magnet:?xt=urn:btih:	0.04283332824707031	0	f	HTTPSConnectionPool(host='t-pb.org', port=443): Max retries exceeded with url: /search/ (Caused by NameResolutionError("HTTPSConnection(host='t-pb.org', port=443): Failed to resolve 't-pb.org' ([Errno -2] Name or service not known)"))	2023-12-03 09:01:27.452522+00	2024-06-22 03:00:02+00	1970-01-01 00:00:00+00	f
28	5	https://x1337x.eu	\N	\N	0.08491802215576172	403	f	Return code not satisfied: 403	2023-07-19 09:56:42.780271+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
55	5	https://1337x.unblockninja.com	\N	\N	0.06948971748352051	0	f	HTTPSConnectionPool(host='1337x.unblockninja.com', port=443): Max retries exceeded with url: /home/ (Caused by NameResolutionError("HTTPSConnection(host='1337x.unblockninja.com', port=443): Failed to resolve '1337x.unblockninja.com' ([Errno -2] Name or service not known)"))	2023-07-24 06:17:44.092808+00	2026-03-25 07:18:26+00	1970-01-01 00:00:00+00	f
42	6	https://cinecalidad.pro	\N	 href=https://cinecalidad.pro/ver-	0.5605316162109375	200	t	\N	2023-07-19 10:43:30.324383+00	2026-08-09 20:25:19+00	1970-01-01 00:00:00+00	f
43	6	https://cinecalidad.vip	\N	 href="https://cinecalidad.vip/pelicula/	0.40647315979003906	200	f	Test match not found	2023-07-19 10:43:30.324383+00	2024-02-10 15:11:03+00	1970-01-01 00:00:00+00	f
44	6	https://cinecalidad.zip	\N	 href="https://cinecalidad.zip/pelicula/	1.572166919708252	0	f	HTTPSConnectionPool(host='cinecalidad.zip', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='cinecalidad.zip', port=443): Failed to resolve 'cinecalidad.zip' ([Errno -2] Name or service not known)"))	2023-07-19 10:43:30.324383+00	2023-08-17 00:43:34+00	1970-01-01 00:00:00+00	t
61	10	https://yts.proxyninja.net	\N	\N	0.02633976936340332	0	f	HTTPSConnectionPool(host='yts.proxyninja.net', port=443): Max retries exceeded with url: /browse-movies (Caused by NameResolutionError("HTTPSConnection(host='yts.proxyninja.net', port=443): Failed to resolve 'yts.proxyninja.net' ([Errno -2] Name or service not known)"))	2023-07-24 06:28:33.243418+00	2024-02-03 13:19:40+00	1970-01-01 00:00:00+00	f
64	10	https://yts.ninjaproxy1.com	\N	\N	0.11748480796813965	403	f	Return code not satisfied: 403	2023-07-24 06:42:36.54238+00	2024-07-12 17:07:04+00	1970-01-01 00:00:00+00	f
21	11	https://ipt.lol	\N	\N	0.2865283489227295	200	t	\N	2023-07-19 09:23:24.653169+00	2026-08-09 20:25:44+00	1970-01-01 00:00:00+00	t
73	6	https://www1.cinecalidad.club	/movie/	 href="https://www1.cinecalidad.club/movie/	2.1041557788848877	200	f	Test match not found	2023-07-24 07:06:28.576669+00	2023-08-14 20:10:43+00	1970-01-01 00:00:00+00	f
25	11	https://ip.workisboring.net	\N	\N	0.2793295383453369	403	f	Return code not satisfied: 403	2023-07-19 09:23:24.653169+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
34	7	https://eztv.re	\N	\N	0.15262317657470703	403	f	Return code not satisfied: 403	2023-07-19 10:16:08.261181+00	2026-03-13 06:16:58+00	1970-01-01 00:00:00+00	f
68	7	https://eztv.proxyninja.net	\N	\N	0.04084658622741699	0	f	HTTPSConnectionPool(host='eztv.proxyninja.net', port=443): Max retries exceeded with url: /home (Caused by NameResolutionError("HTTPSConnection(host='eztv.proxyninja.net', port=443): Failed to resolve 'eztv.proxyninja.net' ([Errno -2] Name or service not known)"))	2023-07-24 06:49:17.0475+00	2024-02-03 13:19:32+00	1970-01-01 00:00:00+00	f
69	7	https://eztv.proxyninja.org	\N	\N	0.13615083694458008	403	f	Return code not satisfied: 403	2023-07-24 06:49:17.0475+00	2025-04-11 12:19:01+00	1970-01-01 00:00:00+00	f
6	12	https://tgx.sb	\N	\N	0.03153276443481445	0	f	HTTPSConnectionPool(host='tgx.sb', port=443): Max retries exceeded with url: /latest (Caused by NameResolutionError("HTTPSConnection(host='tgx.sb', port=443): Failed to resolve 'tgx.sb' ([Errno -2] Name or service not known)"))	2023-07-16 21:34:23.782571+00	2024-10-12 00:58:02+00	1970-01-01 00:00:00+00	t
7	12	https://torrentgalaxy.mx	\N	\N	11.110230684280396	0	f	Timeout 11.0s	2023-07-16 21:34:23.782571+00	2025-02-27 00:40:44+00	1970-01-01 00:00:00+00	t
8	12	https://tgx.rs	\N	\N	0.4885129928588867	0	f	HTTPSConnectionPool(host='tgx.rs', port=443): Max retries exceeded with url: /latest (Caused by SSLError(SSLEOFError(8, '[SSL: UNEXPECTED_EOF_WHILE_READING] EOF occurred in violation of protocol (_ssl.c:1032)')))	2023-07-16 21:34:23.782571+00	2025-02-13 12:19:40+00	1970-01-01 00:00:00+00	t
71	7	https://eztv.abcproxy.org	\N	\N	0.0655674934387207	0	f	HTTPSConnectionPool(host='eztv.abcproxy.org', port=443): Max retries exceeded with url: /home (Caused by NameResolutionError("HTTPSConnection(host='eztv.abcproxy.org', port=443): Failed to resolve 'eztv.abcproxy.org' ([Errno -2] Name or service not known)"))	2023-07-24 06:49:17.0475+00	2024-04-03 08:39:39+00	1970-01-01 00:00:00+00	f
40	8	https://rutracker.nl	\N	\N	0.40534377098083496	0	f	HTTPSConnectionPool(host='rutracker.nl', port=443): Max retries exceeded with url: /forum/index.php (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-19 10:41:04.020578+00	2025-02-10 06:58:27+00	1970-01-01 00:00:00+00	f
37	9	https://torrentdownloads.nocensor.click	\N	\N	0.05992555618286133	0	f	HTTPSConnectionPool(host='torrentdownloads.nocensor.click', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='torrentdownloads.nocensor.click', port=443): Failed to resolve 'torrentdownloads.nocensor.click' ([Errno -2] Name or service not known)"))	2023-07-19 10:19:50.170156+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
22	11	https://ip.findnemo.net	\N	\N	0.2883121967315674	403	f	Return code not satisfied: 403	2023-07-19 09:23:24.653169+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
74	13	https://fitgirl-repacks.123proxy.me	\N	\N	0.5298726558685303	0	f	HTTPSConnectionPool(host='fitgirl-repacks.123proxy.me', port=443): Max retries exceeded with url: / (Caused by SSLError(SSLCertVerificationError(1, '[SSL: CERTIFICATE_VERIFY_FAILED] certificate verify failed: unable to get local issuer certificate (_ssl.c:1032)')))	2023-07-24 10:14:43.189592+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
75	13	https://fitgirl-repacks.proxyer.net	\N	\N	0.033739328384399414	0	f	HTTPSConnectionPool(host='fitgirl-repacks.proxyer.net', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='fitgirl-repacks.proxyer.net', port=443): Failed to resolve 'fitgirl-repacks.proxyer.net' ([Errno -2] Name or service not known)"))	2023-07-24 10:14:43.189592+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
78	13	https://fitgirl-repacks.theproxy.to	\N	\N	0.07650446891784668	0	f	HTTPSConnectionPool(host='fitgirl-repacks.theproxy.to', port=443): Max retries exceeded with url: / (Caused by NameResolutionError("HTTPSConnection(host='fitgirl-repacks.theproxy.to', port=443): Failed to resolve 'fitgirl-repacks.theproxy.to' ([Errno -2] Name or service not known)"))	2023-07-24 10:14:43.189592+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	f
79	15	https://limetorrents.ninjaproxy1.com	\N	\N	0.10610485076904297	403	f	Return code not satisfied: 403	2023-07-24 10:30:44.079216+00	2024-07-12 17:07:36+00	1970-01-01 00:00:00+00	f
24	11	https://ip.venom.global	\N	\N	3.50192928314209	0	f	HTTPSConnectionPool(host='ip.venom.global', port=443): Max retries exceeded with url: / (Caused by SSLError(SSLEOFError(8, '[SSL: UNEXPECTED_EOF_WHILE_READING] EOF occurred in violation of protocol (_ssl.c:1032)')))	2023-07-19 09:23:24.653169+00	1970-01-01 00:00:00+00	1970-01-01 00:00:00+00	t
4	3	https://piratebay.beauty	/recent	<a href="magnet:?xt=urn:btih:	0.35433483123779297	200	f	Test match not found	2023-07-16 12:11:52.05992+00	2024-05-26 22:47:03+00	1970-01-01 00:00:00+00	f
48	3	https://tpbays.top	/recent	<a href="magnet:?xt=urn:btih:	0.5151553153991699	200	f	Test match not found	2023-07-23 09:15:57.548252+00	2023-12-20 14:09:37+00	1970-01-01 00:00:00+00	f
81	13	https://fitgirl--repacks-site.translate.goog/?_x_tr_sl=es&_x_tr_tl=en&_x_tr_hl=en&_x_tr_pto=wapp	\N	\N	0.511786699295044	200	t	\N	2023-07-24 10:43:39.017903+00	2026-08-09 20:26:03+00	1970-01-01 00:00:00+00	f
82	15	https://www-limetorrents-lol.translate.goog/?_x_tr_sl=es&_x_tr_tl=en&_x_tr_hl=en&_x_tr_pto=wapp	https://www-limetorrents-lol.translate.goog/latest100?_x_tr_sl=es&_x_tr_tl=en&_x_tr_hl=en&_x_tr_pto=wapp	\N	0.33069276809692383	403	f	Return code not satisfied: 403	2023-07-24 10:50:43.458292+00	2025-07-11 16:20:38+00	1970-01-01 00:00:00+00	f
99	17	https://solidtorrents.eu	\N	\N	0.5220258235931396	200	t	\N	2024-11-10 20:53:11.255155+00	2026-08-09 20:26:19+00	1970-01-01 00:00:00+00	t
\.



--
-- Data for Name: comments; Type: TABLE DATA; Schema: public; Owner: knabeninfo
--

COPY public.comments (id, content, identifier, "time", web_id, proxy_id) FROM stdin;
1	LimeTorrents says they have official proxies being .asia .co .zone and Limetor.com, but they either are dead or just redirects to the actual domain.	admin	2023-07-24 10:37:58.548897+00	15	\N
3	These proxies are dog shit... Couldn't find much else. Added a google translate proxy to it	admin	2023-07-24 10:43:05.565871+00	13	\N
4	Most of these proxies prevents bots from accessing, thus all proxies appear to be "most likely working"	admin	2023-07-24 10:47:09.727381+00	5	\N
5	This is a test	admin	2023-10-31 05:47:41.473532+00	\N	80
\.


--
-- Data for Name: website_categories; Type: TABLE DATA; Schema: public; Owner: knabeninfo
--

COPY public.website_categories (cat_id, web_id) FROM stdin;
1	4
2	3
4	3
6	3
7	3
8	3
12	3
11	3
7	5
2	5
3	5
4	5
6	5
1	5
14	5
11	5
16	4
15	6
17	8
18	11
19	8
9	3
13	3
5	3
20	3
10	3
20	5
21	10
2	10
21	5
22	4
4	4
5	4
23	4
11	4
7	4
6	4
23	3
21	7
3	7
2	12
3	12
4	12
7	12
6	12
11	12
20	12
21	12
24	12
14	12
24	5
4	8
5	8
8	8
2	8
10	8
6	8
7	8
3	8
11	8
9	8
14	8
22	8
1	11
2	11
3	11
20	11
21	11
24	11
22	11
14	11
13	11
11	11
10	11
9	11
8	11
7	11
6	11
5	11
4	11
3	9
2	9
4	9
1	9
7	9
11	9
6	9
9	9
7	13
6	13
8	13
2	6
3	6
21	6
14	6
22	6
2	15
3	15
4	15
7	15
6	15
8	15
11	15
12	15
13	15
1	15
10	15
9	15
23	15
20	16
1	16
26	16
27	16
23	16
7	16
6	16
22	16
16	16
26	4
1	17
2	17
3	17
4	17
6	17
7	17
11	17
\.


--
-- PostgreSQL database dump complete
--
