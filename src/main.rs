use rocket_dyn_templates::Template;
use rocket_dyn_templates::handlebars::Renderable;
use rocket_dyn_templates::handlebars::{Handlebars, HelperDef, Context, RenderContext, Helper, Output, RenderError};
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
    cxt.elapsed();
    Template::render("index", &cxt)
}

#[get("/website")]
fn website() -> Template
{
    let mut cxt = context::Sorts::new("Website");
    cxt.elapsed();
    Template::render("website", &cxt)
}

#[get("/dashboard")]
fn dashboard() -> Template {
    let mut cxt = context::Sorts::new("Dashboard");
    cxt.logo = Some(Logo::new());
    cxt.logo_kd = Some(LogoKd::new().animate(true));
    cxt.elapsed();
    Template::render("dashboard", &cxt)
}

handlebars_helper!(my_helper: |x: i64, y: i64| x + y);

#[launch]
fn rocket() -> _ {
    let mut hbs = Handlebars::new();
    hbs.register_helper("my_helper", Box::new(my_helper));

    rocket::build()
        .attach(Template::fairing())
        .attach(Template::custom(hbs))
        .attach(static_resources_initializer!(
            "favicon" => "static/img/favicon.ico"
        ))
        .mount("/", routes![favicon])
        .mount("/", routes![index, dashboard, website])

        // Serve the static files
        .mount("/static", FileServer::from("static"))
}