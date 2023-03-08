<?php

namespace App\Bitwise;

class UserLevelOfTraining extends Bitwise
{
  const DONT_ANYTHING = 1;
    const BEGINNER = 1;
    const MIDDLE = 4;
    const EXPERT = 5;

    static $levelOfTraining = [
      "dont_anything",
        "beginner",
        "middle",
        "expert",

    ];

    static $levelOfTrainingValueByBit = [
        1 => "dont_anything",
        1 => "beginner",
        4 => "middle",
        5 => "expert",
    ];

    static $levelOfTrainingBitByValue = [
      "dont_anything" => 1,
        "beginner" => 1,
        "middle" => 4,
        "expert" => 5,
    ];


}
