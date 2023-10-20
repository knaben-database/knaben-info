use std::collections::HashMap;
use serde::Serialize;
use serde_json::Value;

use super::utils;
use logo::Logo;
use logo_kd::LogoKd;

pub mod logo;
pub mod logo_kd;


#[derive(Serialize)]
pub struct Sorts {
    pub name: String,
    pub style_version: String,
    pub main_js_version: String,
    #[serde(skip_serializing_if = "Option::is_none")]
    pub logo: Option<Logo>,
    #[serde(skip_serializing_if = "Option::is_none")]
    pub logo_kd: Option<LogoKd>,
}

impl Sorts {
    pub fn new(name: &str) -> Self {
        Self {
            name: name.to_string(),
            style_version: utils::get_file_modification_time("/static/css/style.css"),
            main_js_version: utils::get_file_modification_time("/js/main.js"),
            logo: None,
            logo_kd: None
        }
    }
}