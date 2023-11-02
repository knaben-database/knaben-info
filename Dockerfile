# Use an official Rust runtime as a parent image
FROM rust:latest

# Set the workdir
WORKDIR /build

# Copy all files in the current directory to the workdir of the image
COPY . .

# Install diesel_cli for database migrations
RUN cargo install diesel_cli --no-default-features --features postgres

# Run migrations
RUN diesel migration run

# Build the application in release mode
RUN cargo build --release

# Make port 8000 available to the world outside this container
EXPOSE 8000

# Run the app when the container launches
CMD ["cargo", "run", "--release"]