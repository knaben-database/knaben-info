use chrono::prelude::*;
use serde::Serialize;
use std::time::Instant;
use std::collections::HashSet;
use std::error::Error;

use super::{db, utils};
use logo::Logo;
use logo_kd::LogoKd;
use crate::db::WebsitesWithProxiesAndClicks;

pub mod logo;
pub mod logo_kd;

#[derive(Serialize, Debug)]
pub struct DateTimeComponents {
    pub year: i32,
    pub month: u32,
    pub day: u32,
    pub hour: u32,
    pub minute: u32,
    pub second: u32,
}
impl DateTimeComponents {
    pub fn new() -> Self {
        let now : DateTime<Utc> = chrono::Utc::now();
        Self {
            year: now.year(),
            month: now.month(),
            day: now.day(),
            hour: now.hour(),
            minute: now.minute(),
            second: now.second(),
        }
    }
}

pub enum Query {
    WebsitesWithClicks,
    RecentWebsites,
    ProxyList,
}

#[derive(Serialize, Default, Debug)]
pub struct QueryResults {
    #[serde(skip_serializing_if = "Option::is_none")]
    pub websites_with_clicks: Option<Vec<WebsitesWithProxiesAndClicks>>,
    // #[serde(skip_serializing_if = "Option::is_none")]
    // pub recent_websites: Option<Vec<RecentWebsites>>,
    // #[serde(skip_serializing_if = "Option::is_none")]
    // pub proxy_list: Option<Vec<ProxyList>>,
}

#[derive(Serialize, Debug)]
pub struct Sorts {
    pub name: String,
    pub style_version: String,
    pub main_js_version: String,
    pub date_time: DateTimeComponents,
    pub load_time: Option<f64>,
    #[serde(skip)]
    start_load_time: Instant,
    #[serde(skip_serializing_if = "Option::is_none")]
    pub logo: Option<Logo>,
    #[serde(skip_serializing_if = "Option::is_none")]
    pub logo_kd: Option<LogoKd>,
    #[serde(skip_serializing_if = "Option::is_none")]
    pub db: Option<QueryResults>
}

impl Sorts {
    pub fn new(name: &str) -> Self {
        Self {
            name: name.to_string(),
            style_version: utils::get_file_modification_time("/static/css/style.css"),
            main_js_version: utils::get_file_modification_time("/js/main.js"),
            date_time: DateTimeComponents::new(),
            start_load_time: Instant::now(),
            load_time: None,
            logo: None,
            logo_kd: None,
            db: None
        }
    }

    pub fn db(mut self, query: Query) -> Self {
        let something = match query {
            Query::WebsitesWithClicks => {
                match db::get_websites_with_clicks() {
                    Ok(websites) => QueryResults { websites_with_clicks: Some(websites), ..Default::default() },
                    Err(_) => panic!("dead")
                }
            },
            _ => todo!("Not implemented yet")
        };
        self.db = Some(something);
        self
    }


    pub fn elapsed(&mut self) {
        self.load_time = Some(self.start_load_time.elapsed().as_nanos() as f64 / 1000000.0);
    }
}