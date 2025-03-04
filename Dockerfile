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
COPY --from=builder /build/static /app/static
COPY --from=builder /build/templates /app/templates
EXPOSE 8000
CMD ["./knabeninfo"]
