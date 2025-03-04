# Build Stage
FROM rust:latest AS builder
WORKDIR /build
COPY . .
RUN cargo build --release

# Runtime stage
FROM debian:stable-slim
RUN apt update && apt install -y libpq5
WORKDIR /app
COPY --from=builder /build/target/release/knabeninfo /app/knabeninfo
EXPOSE 8000
CMD ["./knabeninfo"]
