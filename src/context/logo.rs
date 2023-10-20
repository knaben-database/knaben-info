#[derive(Serialize)]
pub struct Logo {
    color_theme: String,
    core_href_link: Option<String>,
    show_core: bool,
    core_message: Option<String>,
}

impl Logo {
    pub fn new() -> Self {
        Self {
            color_theme: "#00ff72".to_string(),
            core_href_link: None,
            show_core: false,
            core_message: None,
        }
    }

    pub fn color_theme(mut self, color_theme: &str) -> Self {
        self.color_theme = color_theme.to_string();
        self
    }

    pub fn core_href_link(mut self, core_href_link: &str) -> Self {
        self.core_href_link = Some(core_href_link.to_string());
        self
    }

    pub fn show_core(mut self, show_core: bool) -> Self {
        self.show_core = show_core;
        self
    }

    pub fn core_message(mut self, core_message: &str) -> Self {
        self.core_message = Some(core_message.to_string());
        self
    }
}