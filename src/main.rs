use rocket_dyn_templates::Template;
use rocket_dyn_templates::handlebars::Renderable;
use rocket::fs::FileServer;

#[macro_use] extern crate handlebars;
#[macro_use] extern crate serde_json;
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


// Make the favicon appear to be served from root
static_response_handler! {
    "/favicon.ico" => favicon => "favicon",
}


#[get("/")]
fn index() -> Template {
    let mut cxt = context::Sorts::new("Rocket")
        .db(Query::WebsitesWithClicks);

    println!("{:?}", cxt.db);

    cxt.logo = Some(Logo::new().core_href_link("/dashboard"));
    Template::render("index", &cxt)
}

#[get("/website")]
fn website() -> Template
{
    let cxt = context::Sorts::new("Website");
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
        .mount("/", routes![index, dashboard, website])

        // Serve the static files
        .mount("/static", FileServer::from("static"))
}