{
  mkShell,
  lib,
  rust-analyzer-unwrapped,
  rustfmt,
  clippy,
  cargo,
  rustc,
  rustPlatform,
  openssl,
  pkg-config,
  libpq,
}:
mkShell {
  strictDeps = true;

  nativeBuildInputs = [
    cargo
    rustc

    rust-analyzer-unwrapped
    rustfmt
    clippy
    openssl
    pkg-config
  ];

  buildInputs = [
    libpq
  ];

  shellHook = ''
    export PKG_CONFIG_PATH="${openssl.dev}/lib/pkgconfig";
  '';

  env = {
    RUST_SRC_PATH = "${rustPlatform.rustLibSrc}";
  };
}
