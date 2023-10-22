use rocket_dyn_templates::Template;
use rocket::fs::FileServer;
use rocket_db_pools::{deadpool_postgres, Connection, Database};
use sqlx::Connection as OtherConnection;

#[macro_use] extern crate rocket;
#[macro_use] extern crate serde_derive;
#[macro_use] extern crate rocket_include_static_resources;

use context::logo_kd::LogoKd;
use context::logo::Logo;

mod context;
mod utils;
mod db;
mod models;


#[derive(Database)]
#[database("knabeninfo")]
pub struct Db(deadpool_postgres::Pool);


// Make the favicon appear to be served from root
static_response_handler! {
    "/favicon.ico" => favicon => "favicon",
}


#[get("/")]
async fn index(db: &Db) -> Template {
    let mut cxt = context::Sorts::new("Rocket");

    // Acquire a connection from the pool
    let conn = db.get().await.unwrap();
    let websites_with_clicks = db::get_websites_with_clicks(&conn);

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

#[launch]
fn rocket() -> _ {
    rocket::build()
        .attach(Template::fairing())
        .attach(Db::init())
        .attach(static_resources_initializer!(
            "favicon" => "static/img/favicon.ico"
        ))
        .mount("/", routes![favicon])
        .mount("/", routes![index, dashboard, website])

        // Serve the static files
        .mount("/static", FileServer::from("static"))
}