use std::net::IpAddr;
use rocket::Request;
use rocket::request::{FromRequest, Outcome};
use rocket::http::Status;

pub struct IPAddress(pub Option<IpAddr>);

#[rocket::async_trait]
impl<'r> FromRequest<'r> for IPAddress {
    type Error = std::convert::Infallible;

    async fn from_request(request: &'r Request<'_>) -> Outcome<Self, Self::Error> {
        let ip = request.client_ip();
        match ip {
            Some(ip) => Outcome::Success(IPAddress(Some(ip))),
            None => Outcome::Forward(Status::InternalServerError),
        }
    }
}

pub struct UserAgent(pub Option<String>);
#[rocket::async_trait]
impl<'r> FromRequest<'r> for UserAgent {
    type Error = std::convert::Infallible;

    async fn from_request(request: &'r Request<'_>) -> Outcome<Self, Self::Error> {
        let headers = request.headers();
        let user_agent = headers.get_one("User-Agent");
        match user_agent {
            Some(user_agent) => Outcome::Success(UserAgent(Some(user_agent.to_string()))),
            None => Outcome::Forward(Status::InternalServerError),
        }
    }
}