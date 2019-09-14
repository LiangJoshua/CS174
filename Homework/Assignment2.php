<?php

function convertRomanSymbolToNumber($symbol)
{
    if ($symbol == 'I') {
        return 1;
    } else if ($symbol == 'V') {
        return 5;
    } else if ($symbol == 'X') {
        return 10;
    } else if ($symbol == 'L') {
        return 50;
    } else if ($symbol == 'C') {
        return 100;
    } else if ($symbol == 'D') {
        return 500;
    } else if ($symbol == 'M') {
        return 1000;
    } else {
        return - 1;
    }
}

function convertRomanNumeral($roman_numeral)
{
    $modern_numeral = 0;
    if (strlen($roman_numeral) == 0) {
        $modern_numeral = "Empty Input not allowed.";
    }
    for ($i = 0; $i < strlen($roman_numeral); $i ++) {
        $x = convertRomanSymbolToNumber($roman_numeral[$i]);
        if ($x == - 1) {
            $modern_numeral = "Input contains invalid roman numeral symbol.";
            break;
        }
        if ($i + 1 < strlen($roman_numeral)) {
            $y = convertRomanSymbolToNumber($roman_numeral[$i + 1]);
            if ($x < $y) {
                $modern_numeral = $modern_numeral + $y - $x;
                $i ++;
            } else {
                $modern_numeral = $modern_numeral + $x;
            }
        } else {
            $modern_numeral = $modern_numeral + $x;
            $i ++;
        }
    }
    return $modern_numeral;
}

function testConvertRomanNumeral()
{
    $input_array = array(
        "VI",
        "IV",
        "VIII",
        "VIIII",
        "IX",
        "MCMXC",
        "IX",
        "7",
        "VG",
        "-1",
        -1,
        ""
    );
    $output_array = array(
        6,
        4,
        8,
        9,
        9,
        1990,
        9,
        "Input contains invalid roman numeral symbol.",
        "Input contains invalid roman numeral symbol.",
        "Input contains invalid roman numeral symbol.",
        "Input contains invalid roman numeral symbol.",
        "Empty Input not allowed."
    );
    foreach ($input_array as &$value) {
        echo "{$value} => ";
        $value = convertRomanNumeral($value);
        echo "{$value}\n";
    }
    if ($input_array === $output_array) {
        print("Tests Passed!");
    } else {
        print("Tests Failed!");
    }
}

testConvertRomanNumeral();