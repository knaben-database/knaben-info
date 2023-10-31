use chrono::{DateTime, Utc};
use diesel::{AsChangeset, Insertable, Queryable, Selectable};
use diesel::prelude::*;

#[derive(Queryable, Selectable, Insertable, AsChangeset, Debug, Clone)]
#[diesel(table_name = crate::db::schema::website)]
#[diesel(check_for_backend(diesel::pg::Pg))]
pub struct Website {
    pub id: i32,
    pub name: String,
    pub url: String,
    pub description: Option<String>,
    pub ping: f64,
    pub return_code: i32,
    pub up: bool,
    pub test_match: String,
    pub test_path: String,
    pub test_reason: Option<String>,
    pub added_time: DateTime<Utc>,
    pub last_active: DateTime<Utc>,
    pub last_checked: DateTime<Utc>,
}