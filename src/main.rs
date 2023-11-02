use rocket_dyn_templates::Template;
use rocket::fs::FileServer;


#[macro_use] extern crate rocket;
#[macro_use] extern crate serde_derive;
#[macro_use] extern crate rocket_include_static_resources;

use context::logo_kd::LogoKd;
use context::logo::Logo;
use crate::context::Query;

mod context;
mod utils;
mod db;
mod models;
mod hbs;
mod request_guards;
mod api;


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


#[launch]
fn rocket() -> _ {
    rocket::build()
        .attach(Template::custom(|engines| hbs::register_helpers(&mut engines.handlebars)))
        .attach(static_resources_initializer!(
            "favicon" => "static/img/favicon.ico"
        ))
        .mount("/", routes![favicon])
        .mount("/", routes![index, dashboard, website, api::click])

        // Serve the static files
        .mount("/static", FileServer::from("static"))
}