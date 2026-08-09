# Build stage
FROM rust:1-trixie AS builder

RUN apt update && apt install -y \
    libssl-dev \
    pkg-config \
    libpq-dev

# Install diesel CLI
RUN cargo install diesel_cli --no-default-features --features postgres

WORKDIR /app
COPY . .
RUN cargo build --release

# Runtime stage
FROM debian:trixie-slim
RUN apt update && apt install -y \
    libpq5 \
    ca-certificates \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy binary and diesel CLI
COPY --from=builder /app/target/release/knabeninfo /usr/local/bin/knabeninfo
COPY --from=builder /usr/local/cargo/bin/diesel /usr/local/bin/diesel

# Copy assets
COPY --from=builder /app/static ./static
COPY --from=builder /app/templates ./templates
COPY --from=builder /app/migrations ./migrations
COPY --from=builder /app/Rocket.toml ./

EXPOSE 8000
CMD ["knabeninfo"]
