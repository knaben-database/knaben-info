use rocket::fairing::{Fairing, Info, Kind};
use rocket_dyn_templates::Template;
use rocket_dyn_templates::handlebars::Renderable;
use rocket::fs::FileServer;
use rocket::http::Status;
use rocket::request::FromRequest;


#[macro_use] extern crate serde_json;
#[macro_use] extern crate rocket;
#[macro_use] extern crate serde_derive;
#[macro_use] extern crate rocket_include_static_resources;

use context::logo_kd::LogoKd;
use context::logo::Logo;
use crate::context::Query;
use crate::request_guards::IPAddress;

mod context;
mod utils;
mod db;
mod models;
mod hbs;
mod request_guards;


// Make the favicon appear to be served from root
static_response_handler! {
    "/favicon.ico" => favicon => "favicon",
}


#[get("/")]
fn index() -> Template {
    let mut cxt = context::Sorts::new("Rocket")
        .db(Query::WebsitesWithClicks(None));

    cxt.logo = Some(Logo::new().core_href_link("/dashboard"));
    Template::render("index", &cxt)
}

#[get("/website/<website_name>")]
fn website(website_name: String) -> Template {
    let mut cxt = context::Sorts::new("Website")
        .db(Query::WebsitesWithClicks(Some(website_name)))
        .db(Query::ProxyWithClicks(None));

    cxt.logo = Some(Logo::new());
    cxt.logo_kd = Some(LogoKd::new().animate(true));

    // TODO: 404 on not found

    Template::render("website", &cxt)
}

#[get("/dashboard")]
fn dashboard() -> Template {
    let mut cxt = context::Sorts::new("Dashboard");
    cxt.logo = Some(Logo::new());
    cxt.logo_kd = Some(LogoKd::new().animate(true));
    Template::render("dashboard", &cxt)
}


#[head("/clicks")]
fn click(ip: IPAddress) -> Status {
    match ip.0 {
        Some(ip) => println!("Your IP address is {}", ip),
        None => println!("Couldn't get your IP address."),
    };
    Status::Ok
}


#[launch]
fn rocket() -> _ {
    rocket::build()
        .attach(Template::custom(|engines| hbs::register_helpers(&mut engines.handlebars)))
        .attach(static_resources_initializer!(
            "favicon" => "static/img/favicon.ico"
        ))
        .mount("/", routes![favicon])
        .mount("/", routes![index, dashboard, website, click])

        // Serve the static files
        .mount("/static", FileServer::from("static"))
}