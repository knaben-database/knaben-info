use rocket::http::Status;
use sha2::{Sha256, Digest};
use regex::Regex;

use crate::db;
use crate::request_guards::{IPAddress, UserAgent};


#[head("/api/clicks/<proxy_id>")]
pub fn click(ip: IPAddress, user_agent: UserAgent, proxy_id: i32) -> Status {
    if ip.0.is_none() {
        return Status::BadRequest;
    }
    let ip = ip.0.unwrap();

    let identity_string = match user_agent.0 {
        Some(ua) => {
            let re = Regex::new(r"[^a-z]+").unwrap();
            let ua = ua.to_lowercase();
            let ua = re.replace_all(&ua[..], "-");
            format!("{}{}", ip.to_string(), ua)
        },
        None => ip.to_string()
    };

    let mut hasher = Sha256::new();
    hasher.update(identity_string);
    let identity = format!("{:x}", hasher.finalize());


    match db::insert_click(identity, proxy_id) {
        Ok(_) => Status::Ok,
        Err(err) => {
            println!("{}", err);
            Status::InternalServerError
        }
    }
}