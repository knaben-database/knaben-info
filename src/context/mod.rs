use chrono::prelude::*;
use serde::Serialize;
use std::time::SystemTime;

use super::{db, utils};
use logo::Logo;
use logo_kd::LogoKd;
use crate::db::{WebsitesWithProxiesAndClicks, ProxiesAndClicks};

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
    WebsitesWithClicks(Option<String>),
    RecentWebsites,
    ProxyList,
    ProxyWithClicks(Option<i32>)
}

#[derive(Serialize, Default, Debug)]
pub struct QueryResults {
    #[serde(skip_serializing_if = "Option::is_none")]
    pub websites_with_clicks: Option<Vec<WebsitesWithProxiesAndClicks>>,
    #[serde(skip_serializing_if = "Option::is_none")]
    pub proxies_with_clicks: Option<Vec<ProxiesAndClicks>>,
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
    pub start_load_time: f64,
    #[serde(skip_serializing_if = "Option::is_none")]
    pub status_info: Option<(u16, String)>,
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
            start_load_time: SystemTime::now().duration_since(SystemTime::UNIX_EPOCH).unwrap().as_secs_f64(),
            status_info: None,
            logo: None,
            logo_kd: None,
            db: None
        }
    }

    pub fn db(mut self, query: Query) -> Self {
        match query {
            Query::WebsitesWithClicks(wn) => {
                match db::get_websites_with_clicks(wn) {
                    Ok(websites) => {
                        let mut results = self.db.unwrap_or_else(Default::default);
                        results.websites_with_clicks = Some(websites);
                        self.db = Some(results);
                    },
                    Err(_) => panic!("dead")
                }
            },
            Query::ProxyWithClicks(wi) => {
                let id = wi.or_else(|| self.db.as_ref()?.websites_with_clicks.as_ref()?.get(0).map(|wwpac|wwpac.id));
                match db::get_proxies_with_clicks(id) {
                    Ok(proxies) => {
                        let mut results = self.db.unwrap_or_else(Default::default);
                        results.proxies_with_clicks = Some(proxies);
                        self.db = Some(results);
                    },
                    Err(_) => panic!("dead")
                }
            },
            Query::ProxyList => {},
            Query::RecentWebsites => {}
        };
        self
    }

    pub fn status(mut self, status_code: u16, msg: String) -> Self {
        self.status_info = Some((status_code, msg));
        self
    }
}