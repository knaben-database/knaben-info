use chrono::{DateTime};
use sqlx;

#[derive(sqlx::FromRow, Debug)]
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
    pub added_time: chrono::NaiveDateTime,
    pub last_active: chrono::NaiveDateTime,
    pub last_checked: chrono::NaiveDateTime,
}