# Build Stage
FROM rust:latest as builder
WORKDIR /build
COPY . .
RUN cargo install diesel_cli --no-default-features --features postgres
RUN diesel migration run
RUN cargo build --release

# Runtime stage
FROM debian:stable-slim
RUN apt update && apt install -y libpq5
WORKDIR /app
COPY --from=builder /build/. /app/
EXPOSE 8000
CMD ["./target/release/knabeninfo"]