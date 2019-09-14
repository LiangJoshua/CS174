<?php

function primeNumbersUpTo($limit){
    $number = 2 ;
    $primeNumbers = "";
    while ($number < $limit )
    {
        $div_count=0;
        for ( $i=1;$i<=$number;$i++)
        {
            if (($number%$i)==0)
            {
                $div_count++;
            }
        }
        if ($div_count<3)
        {
            $primeNumbers = $primeNumbers.$number.", ";
        }
        $number=$number+1;
    }
    return $primeNumbers;
}

function testPrimeNumbersUpTo(){
    if(primeNumbersUpTo(0) == ""){
        print("Test 1 passed\n");
    }
    if(primeNumbersUpTo(10) == "2, 3, 5, 7, "){
        print("Test 2 passed\n");
    }
    if(primeNumbersUpTo(20) == "2, 3, 5, 7, 11, 13, 17, 19, "){
        print("Test 2 passed\n");
    }
}

testPrimeNumbersUpTo();
