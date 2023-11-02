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