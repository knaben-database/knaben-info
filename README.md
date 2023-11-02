# Knaben.info website

Status list of various proxy websites.

## Setup

You can start by cloning the repository, and cd into it:

```bash
$ git clone https://git.knaben.info/Knaben-Database/knaben.info.git
$ cd knaben.info
```

### Dependencies

* Postgres 15
* Rust
* Docker (optional)

### Prequels

Before you can build and run the application, you need to set up the appropriate environment variables.
We recommend you do this in a `.env` file. Diesel and rocket will automatically look for this file.

```
DATABASE_URL=postgres://postgres:password@172.17.0.1:5434/knabeninfo
ROCKET_ADDRESS=0.0.0.0
```

### Run with docker

```bash
$ docker build --add-host host.docker.internal:host-gateway -t knabeninfo .
```

```bash
$ docker run -p 8000:8000 --network=host --add-host host.docker.internal:host-gateway -e ROCKET_ADDRESS=0.0.0.0 knabeninfo:latest
```

### Run from source

```bash
$ cargo build --release
$ ./target/release/knabeninfo
```