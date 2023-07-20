<?php

declare(strict_types=1);

class Math
{
    /** Thank you Eyel <3
     * @param float<0,1> $x The percentage to curve
     * @param float<0,> $steepness The steepness
     * @return float
     */
    public static function curve(float $x, float $steepness):float{

        $a = (1-2*($steepness/($steepness-1))) / (1 + pow($steepness, 2*$x-1));
        $b = $steepness/($steepness-1);

        return $a + $b;
    }

    /** En bakketopp
     * @param float<0,1> $x The percentage to curve.
     * @param float<0,> $steepness The steepness. The smaller it is, the stepper it gets
     * @return float
     */
    public static function easeOut(float $x, float $steepness):float{
        return 1 - ((1 - pow($x, $steepness)) / (pow($x, $steepness) + 1));
    }
}