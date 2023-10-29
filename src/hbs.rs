use rocket::fairing::AdHoc;
use rocket::Rocket;
use handlebars::Handlebars;
use rocket_dyn_templates::handlebars::handlebars_helper;

handlebars_helper!(uppercase: |s: str| s.to_uppercase());
handlebars_helper!(length: |s: str| s.len());

