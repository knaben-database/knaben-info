use rocket::fairing::AdHoc;
use rocket::Rocket;
use rocket_dyn_templates::handlebars::handlebars_helper;
use rocket_dyn_templates::handlebars::{Handlebars, HelperDef, Context, RenderContext, Helper, Output, RenderError};
use crate::utils;


fn multiply_by_thousand_and_round(h: &Helper, _: &Handlebars, _: &Context, _: &mut RenderContext, out: &mut dyn Output) -> Result<(), RenderError> {
    // Get the parameter from the helper, which should be a number
    let param = h.param(0).unwrap().value();
    let number = param.as_f64().unwrap();

    // Multiply the number by 1000 and round it
    let result = (number * 1000.0).round();

    // Write the result to the output
    out.write(&result.to_string())?;
    Ok(())
}

fn name_to_safe_clickable(h: &Helper, _: &Handlebars, _: &Context, _: &mut RenderContext, out: &mut dyn Output) -> Result<(), RenderError> {
    // Get the parameter from the helper, which should be a number
    let param = h.param(0).unwrap().value();
    let name = param.as_str().unwrap();

    let result = name.to_lowercase().replace(|c: char| !c.is_ascii_alphanumeric(), "-");

    // Write the result to the output
    out.write(&result)?;
    Ok(())
}

fn colors_between_offset_ease_out(h: &Helper, _: &Handlebars, _: &Context, _: &mut RenderContext, out: &mut dyn Output) -> Result<(), RenderError> {
    let color1 = h.param(0).unwrap().value().as_str().unwrap();
    let color2 = h.param(1).unwrap().value().as_str().unwrap();
    let min_value = h.param(2).unwrap().value().as_f64().unwrap();
    let max_value = h.param(3).unwrap().value().as_f64().unwrap();
    let value = h.param(4).unwrap().value().as_f64().unwrap();
    let steepness = h.param(5).unwrap().value().as_f64().unwrap();

    // Parse the colors
    let r1 = u8::from_str_radix(&color1[1..3], 16).unwrap();
    let g1 = u8::from_str_radix(&color1[3..5], 16).unwrap();
    let b1 = u8::from_str_radix(&color1[5..7], 16).unwrap();

    let r2 = u8::from_str_radix(&color2[1..3], 16).unwrap();
    let g2 = u8::from_str_radix(&color2[3..5], 16).unwrap();
    let b2 = u8::from_str_radix(&color2[5..7], 16).unwrap();

    // Calculate the ratio
    let ratio = if value * 1000.0 - min_value * 1000.0 > 0.0 {
        utils::mafs::ease_out((value * 1000.0 - min_value * 1000.0) / (max_value * 1000.0 - min_value * 1000.0), steepness)
    } else { 0.0 };

    // Interpolate the colors
    let r = r1 as f64 * (1.0 - ratio) + r2 as f64 * ratio;
    let g = g1 as f64 * (1.0 - ratio) + g2 as f64 * ratio;
    let b = b1 as f64 * (1.0 - ratio) + b2 as f64 * ratio;

    // Write the color to the output in hexadecimal format
    out.write(&format!("#{:02X}{:02X}{:02X}", r.round() as u8, g.round() as u8, b.round() as u8))?;
    Ok(())
}

fn colors_between_offset(h: &Helper, _: &Handlebars, _: &Context, _: &mut RenderContext, out: &mut dyn Output) -> Result<(), RenderError> {
    // Get the parameters from the helper
    let color1 = h.param(0).unwrap().value().as_str().unwrap();
    let color2 = h.param(1).unwrap().value().as_str().unwrap();
    let min_value = h.param(2).unwrap().value().as_f64().unwrap();
    let max_value = h.param(3).unwrap().value().as_f64().unwrap();
    let value = h.param(4).unwrap().value().as_f64().unwrap();

    // Parse the colors
    let r1 = u8::from_str_radix(&color1[1..3], 16).unwrap();
    let g1 = u8::from_str_radix(&color1[3..5], 16).unwrap();
    let b1 = u8::from_str_radix(&color1[5..7], 16).unwrap();

    let r2 = u8::from_str_radix(&color2[1..3], 16).unwrap();
    let g2 = u8::from_str_radix(&color2[3..5], 16).unwrap();
    let b2 = u8::from_str_radix(&color2[5..7], 16).unwrap();

    // Calculate the ratio
    let ratio = if value * 1000.0 - min_value * 1000.0 > 0.0 {
        (value * 1000.0 - min_value * 1000.0) / (max_value * 1000.0 - min_value * 1000.0)
    } else { 0.0 };

    // Interpolate the colors
    let r = r1 as f64 * (1.0 - ratio) + r2 as f64 * ratio;
    let g = g1 as f64 * (1.0 - ratio) + g2 as f64 * ratio;
    let b = b1 as f64 * (1.0 - ratio) + b2 as f64 * ratio;

    // Write the color to the output in hexadecimal format
    out.write(&format!("#{:02X}{:02X}{:02X}", r.round() as u8, g.round() as u8, b.round() as u8))?;
    Ok(())
}

// Then you can register your helper like this:
pub fn register_helpers(registry: &mut Handlebars) {
    registry.register_helper("multiply_by_thousand_and_round", Box::new(multiply_by_thousand_and_round));
    registry.register_helper("colors_between_offset", Box::new(colors_between_offset));
    registry.register_helper("colors_between_offset_ease_out", Box::new(colors_between_offset_ease_out));
    registry.register_helper("name_to_safe_clickable", Box::new(name_to_safe_clickable));
}
