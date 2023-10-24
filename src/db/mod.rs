use chrono::{DateTime, NaiveDateTime, Utc};
use rocket_db_pools::deadpool_postgres;
use rocket_db_pools::deadpool_postgres::GenericClient;
use sqlx::error::DatabaseError;
use crate::Db;

mod schema;

struct Comment {
    id: i32,
    web_id: i32,
    proxy_id: Option<i32>,
    time: NaiveDateTime,
    content: String,
    identifier: String
}

struct ProxyList {
    id: i32,
    web_id: i32,
    url: String,
    test_path: Option<String>,
    test_match: Option<String>,
    ping: f64,
    return_code: i32,
    up: bool,
    test_reason: Option<String>,
    added_time: NaiveDateTime,
    last_active: NaiveDateTime,
    last_checked: NaiveDateTime,
    official: bool,
    clicks_last_24h: u64,
    clicks: u64
}

struct WebsitesWithProxiesAndClicks {
    id: i32,
    name: String,
    description: String,
    url: String,
    ping: f64,
    return_code: i32,
    up: bool,
    test_reason: Option<String>,
    added_time: NaiveDateTime,
    last_active: NaiveDateTime,
    last_checked: NaiveDateTime,
    categories: Vec<String>,
    clicks: u64,
    clicks_last_24h: u64,
    proxy_list: Vec<ProxyList>,
    comments: Vec<Comment>
}

pub async fn get_websites_with_clicks(client: &deadpool_postgres::Client) -> Result<Vec<WebsitesWithProxiesAndClicks>, Box<dyn std::error::Error>> {
    let stmt = client.prepare(
        r#"
        SELECT
            w.id,
            w.name,
            w.description,
            w.url,
            w.ping,
            w.return_code,
            w.up,
            w.test_reason,
            w.added_time,
            w.last_active,
            w.last_checked,
            COALESCE(cats.names, '[]') as categories,
            COUNT(c.proxy_id) as clicks,
            COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) as clicks_last_24h,
            COALESCE(pl.proxy_list, '[]') as proxy_list,
            COALESCE(cmt.comments, '[]') as comments
        FROM
            website w
        LEFT JOIN (
            SELECT
                jsonb_agg(cat.name) as names,
                wc.web_id
            FROM
                categories cat
            INNER JOIN website_categories wc on cat.id = wc.cat_id
            GROUP BY wc.web_id
        ) cats ON cats.web_id = w.id
        LEFT JOIN proxy_list pl2 ON pl2.web_id = w.id
        LEFT JOIN clicks c ON c.proxy_id = pl2.id
        LEFT JOIN (
            SELECT
                web_id,
                jsonb_agg(row_to_json(c)) as comments
            FROM (
                SELECT *
                FROM comments
                ORDER BY "time" DESC
                LIMIT 5
            ) c
            GROUP BY web_id
        ) cmt ON cmt.web_id = w.id
        LEFT JOIN (
            SELECT
                web_id,
                jsonb_agg(row_to_json(pl2) ORDER BY clicks_last_24h DESC) as proxy_list
            FROM (
                SELECT
                    id,
                    web_id,
                    url,
                    official,
                    ping,
                    return_code,
                    up,
                    test_reason,
                    added_time,
                    last_active,
                    last_checked,
                    COUNT(c.proxy_id) clicks,
                    COUNT(CASE WHEN c.time >= NOW() - INTERVAL '24 HOURS' THEN c.proxy_id END) as clicks_last_24h
                FROM proxy_list pl3
                LEFT JOIN clicks c on pl3.id = c.proxy_id
                WHERE
                    up = True OR
                    return_code = 403 OR
                    return_code = 401
                GROUP BY pl3.id
                ORDER BY clicks_last_24h DESC
            ) pl2
            GROUP BY web_id
        ) pl ON pl.web_id = w.id
        GROUP BY
            w.id, cats.names, pl.proxy_list, cmt.comments
        ORDER BY
            clicks DESC;

        "#)
        .await?;

    let rows = client.query(&stmt, &[]).await?;

    let mut websites = Vec::new();

    for row in rows {
        let website = WebsitesWithProxiesAndClicks {
            id: row.get("id"),
            name: row.get("name"),
            description: row.get("description"),
            url: row.get("url"),
            ping: row.get("ping"),
            return_code: row.get("return_code"),
            up: row.get("up"),
            test_reason: row.get("test_reason"),
            added_time: row.get("added_time"),
            last_active: row.get("last_active"),
            last_checked: row.get("last_checked"),
            categories: serde_json::from_value(row.get("categories"))?,
            clicks: row.get("clicks"),
            clicks_last_24h: row.get("clicks_last_24h"),
            proxy_list: serde_json::from_value(row.get("proxy_list"))?,
            comments: serde_json::from_value(row.get("comments"))?,
        };

        websites.push(website);
    }

    Ok(websites)
}