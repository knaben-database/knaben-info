use std::env;
use chrono::{DateTime, NaiveDateTime, Utc};
use diesel::{Connection, PgConnection};
use diesel::prelude::*;
use dotenvy::dotenv;
use crate::db::schema::front_page::dsl::front_page;
use crate::models::Website;

pub mod schema;

fn establish_connection() -> PgConnection {
    dotenv().ok();

    let database_url = env::var("DATABASE_URL").expect("DATABASE_URL must be set");
    PgConnection::establish(&database_url)
        .unwrap_or_else(|_| panic!("Error connecting to {database_url}"))
}

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
    added_time: DateTime<Utc>,
    last_active: DateTime<Utc>,
    last_checked: DateTime<Utc>,
    official: bool,
    clicks_last_24h: u64,
    clicks: u64
}

#[derive(Queryable, Selectable, Insertable, Serialize, Debug, Clone)]
#[diesel(table_name = crate::db::schema::front_page)]
#[diesel(check_for_backend(diesel::pg::Pg))]
pub struct WebsitesWithProxiesAndClicks {
    id: i32,
    name: String,
    description: Option<String>,
    url: String,
    ping: f64,
    return_code: i32,
    is_restricted: bool,
    up: bool,
    test_reason: Option<String>,
    added_time: DateTime<Utc>,
    last_active: DateTime<Utc>,
    last_checked: DateTime<Utc>,
    categories: serde_json::Value,
    clicks: i64,
    clicks_last_24h: i64,
    proxy_list: serde_json::Value,
    comments: serde_json::Value
}

pub fn get_websites_with_clicks() -> Result<Vec<WebsitesWithProxiesAndClicks>, Box<dyn std::error::Error>> {
    use schema::front_page::dsl::*;

    let conn = &mut establish_connection();

    let results: Vec<WebsitesWithProxiesAndClicks> = front_page
        .select(WebsitesWithProxiesAndClicks::as_select())
        .load(conn)?;

    Ok(results)
}

pub fn get_websites() -> Vec<Website> {
    use schema::website::dsl::*;

    let conn = &mut establish_connection();

    let results: Vec<Website> = website
        .limit(5)
        .select(Website::as_select())
        .load(conn)
        .expect("Error loading posts with usernames");

    results
}