pub fn curve(x: f32, steepness: f32) -> f32 {

    let a = (1.0-2.0*(steepness/(steepness-1.0))) / (1.0 + f32::powf(steepness, 2.0*x-1.0));
    let b = steepness/(steepness-1.0);

    a + b
}

/** En bakketopp
    * @param float<0,1> $x The percentage to curve.
    * @param float<0,> $steepness The steepness. The smaller it is, the stepper it gets
    * @return float
 */
pub fn ease_out(x: f64, steepness: f64) -> f64 {
    return 1.0 - ((1.0 - f64::powf(x, steepness)) / (f64::powf(x, steepness) + 1.0));
}