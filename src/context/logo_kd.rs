#[derive(Serialize, Debug)]
pub struct LogoKd {
    animate: bool,
}

impl LogoKd {
    pub fn new() -> Self {
        Self {
            animate: false,
        }
    }

    pub fn animate(mut self, animate: bool) -> Self {
        self.animate = animate;
        self
    }
}