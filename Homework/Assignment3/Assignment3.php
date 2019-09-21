<?php
echo <<<_END
       <html><head><title>PHP Form Upload</title></head><body>
        <form method='post' action='Assignment3.php' enctype='multipart/form-data'>
                Select a text file with exactly and only 1000 numbers: <input type='file' name='filename' size='10'>
                <input type='submit' value='Upload'>
        </form>
_END;
if ($_FILES) {
    $file_name = $_FILES['filename']['name'];
    $file_type = $_FILES['filename']['type'];

    if ($file_type == 'text/plain') {
        $ext = txt;
    }
    if ($file_name != '') {
        if ($ext) {
            $name = "numbers.$ext";
            move_uploaded_file($_FILES['filename']['tmp_name'], $name);
            echo "Uploaded file $file_name as $name<br><br>";

            $file = fopen($name, 'r') or die("File does not exist or you lack permission to open it");
            $string = fread($file, filesize($name));
            fclose($file);
            $filtered_string = str_replace("\n", '', $string);
            $filtered_string = str_replace("\r", '', $filtered_string);
            if (strlen($filtered_string) != 1000) {
                unlink($name);
                echo "$name has been discarded because it didn't contain exactly 1000 numbers<br>";
            } else if (! ctype_digit($filtered_string)) {
                unlink($name);
                echo "$name has been discarded because it contains some non-number characters<br>";
            } else {
                $max_product = maxProductOfFive($filtered_string);
                $five_adjacent_numbers = fiveAdjacentNumbers($filtered_string);
                $factorial_sum = factorialSum($max_product);
                echo "Find the 5 adjacent numbers that multiplied together give the largest product:<br>5 adjacent numbers: $five_adjacent_numbers<br>Product: $max_product<br><br>";
                echo "Given the product from above, compute the sum of the factorial of each term of the product:<br>$factorial_sum<br><br>";
            }
        } else {
            print("$file_name is not an accepted text file\n");
        }
    } else {
        print("Please select a file");
    }
} else
    echo "No file has been uploaded";
echo "</body></html>";

function maxProductOfFive($string)
{
    $filtered_string = str_replace(PHP_EOL, '', $string);
    if (strlen($filtered_string) != 1000) {
        return "Must contain exactly 1000 numbers";
    }
    $product = 1;
    $max_product = 1;
    for ($i = 0; $i < strlen($filtered_string) - 5; $i ++) {
        $adjacent_string = substr($filtered_string, $i, 5);
        for ($j = 0; $j < 5; $j ++) {
            if (! ctype_digit($adjacent_string[$j])) {
                return "Can't contain non-numbers";
            }
            $product = $product * intval($adjacent_string[$j]);
        }
        if ($product > $max_product) {
            $max_product = $product;
        }
        $product = 1;
    }
    return $max_product;
}

function fiveAdjacentNumbers($string)
{
    $filtered_string = str_replace(PHP_EOL, '', $string);
    if (strlen($filtered_string) != 1000) {
        return "Must contain exactly 1000 numbers";
    }
    $product = 1;
    $max_product = 1;
    $adjacent_numbers = "";
    for ($i = 0; $i < strlen($filtered_string) - 5; $i ++) {
        $adjacent_string = substr($filtered_string, $i, 5);
        for ($j = 0; $j < 5; $j ++) {
            if (! ctype_digit($adjacent_string[$j])) {
                return "Can't contain non-numbers";
            }
            $product = $product * intval($adjacent_string[$j]);
        }
        if ($product > $max_product) {
            $max_product = $product;
            $adjacent_numbers = $adjacent_string;
        }
        $product = 1;
    }
    return $adjacent_numbers;
}

function factorialSum($max_product)
{
    $string_max_product = strval($max_product);
    $factorial_sum = 0;
    for ($i = 0; $i < strlen($string_max_product); $i ++) {
        if (! ctype_digit($string_max_product[$i])) {
            return "Can't contain non-numbers";
        }
        $factorial_sum = $factorial_sum + factorial($string_max_product[$i]);
    }
    return $factorial_sum;
}

function factorial($number)
{
    $factorial = 1;
    for ($i = 1; $i <= $number; $i ++) {
        $factorial = $factorial * $i;
    }
    return $factorial;
}

function test()
{
    $first_test = "71636269561882670428252483600823257530420752963450
85861560789112949495459501737958331952853208805511
65727333001053367881220235421809751254540594752243
52584907711670556013604839586446706324415722155397
53697817977846174064955149290862569321978468622482
83972241375657056057490261407972968652414535100474
82166370484403199890008895243450658541227588666881
96983520312774506326239578318016984801869478851843
12540698747158523863050715693290963295227443043557
66896648950445244523161731856403098711121722383113
05886116467109405077541002256983155200055935729725
16427171479924442928230863465674813919123162824586
17866458359124566529476545682848912883142607690042
24219022671055626321111109370544217506941658960408
07198403850962455444362981230987879927244284909188
84580156166097919133875499200524063689912560717606
62229893423380308135336276614282806444486645238749
73167176531330624919225119674426574742355349194934
30358907296290491560440772390713810515859307960866
70172427121883998797908792274921901699720888093776";
    $max_product_first_test = maxProductOfFive($first_test);
    $five_adjacent_first_test = fiveAdjacentNumbers($first_test);
    $factorial_sum_first_test = factorialSum($max_product_first_test);

    $second_test = "0380440762555308044737435776192890657887336499550552939900366241995025862761884584394057978428475365681875751267378554077873371978225037887084858909595591412667533359448376960790170445077833722986536531885013787498432053327608773994812471787595569328961000249944527235545341971598989537492179234969636270556161558916863572613152093593542494655123920531750390526990634594639922296717108869793906914140453454843944063947509879329106526821865933854751406594797628424196394194244475006380161557309037761170816491896213736616182575241568093540855628178811168423285112588563595541784459497105299991615979054000308232682964205244369349480604917348040882819567624978233693919767028001018529134696022205047579536610377078728443613488285520165624663662488682756322590292524155474236881744297617029607203820130149811898205681522936479556060627847668140188813986881113100602704945698849278472953068894640753332777784904151158442053720173283271978589056354434616211700333731551689356224606389270033257601417504696";
    $max_product_second_test = maxProductOfFive($second_test);
    $five_adjacent_second_test = fiveAdjacentNumbers($second_test);
    $factorial_sum_second_test = factorialSum($max_product_second_test);

    $third_test = "1232103901293102";
    $max_product_third_test = maxProductOfFive($third_test);
    $five_adjacent_third_test = fiveAdjacentNumbers($third_test);
    $factorial_sum_third_test = factorialSum($max_product_third_test);

    $fourth_test = "71636269561882670428252483600823257530420752963450
85861560789112949495459501737958331952853208805511
65727333001053367881220235421809751254540594752243
52584907711670556013604839586446706324415722155397
53697817977846174064955149290862569321978468622482
83972241375657056057490261407972968652414535100474
82166370484403199890008895243450658541227588666881
96983520312774506326239578318016984801869478851843
12540698747158523863050715693290963295227443zz3557
66896648950445244523161731856403098711121722383113
05886116467109405077541002256983155200055935729725
16427171479924442928230863465674813919123162824586
17866458359124566529476545682848912883142607690042
24219022671055626321111109370544217506941658960408
07198403850962455444362981230987879927244284909188
84580156166097919133875499200524063689912560717606
62229893423380308135336276614282806444486645238749
73167176531330624919225119674426574742355349194934
30358907296290491560440772390713810515859307960866
70172427121883998797908792274921901699720888093776";
    $max_product_fourth_test = maxProductOfFive($fourth_test);
    $five_adjacent_fourth_test = fiveAdjacentNumbers($fourth_test);
    $factorial_sum_fourth_test = factorialSum($max_product_fourth_test);

    $result = "Tests pass!";

    if ($max_product_first_test != 40824 || $five_adjacent_first_test != 99879 || $factorial_sum_first_test != 40371) {
        echo ("Test 1 failed<br>");
        $result = "Tests failed!";
    }
    if ($max_product_second_test != 46656 || $five_adjacent_second_test != 98989 || $factorial_sum_second_test != 2304) {
        echo ("Test 2 failed!<br>");
        $result = "Tests failed!";
    }
    if ($max_product_third_test != "Must contain exactly 1000 numbers" || $five_adjacent_third_test != "Must contain exactly 1000 numbers" || $factorial_sum_third_test != "Can't contain non-numbers") {
        echo ("Test 3 failed!");
        $result = "Tests fail!";
    }
    if ($max_product_fourth_test != "Can't contain non-numbers" || $five_adjacent_fourth_test != "Can't contain non-numbers" || $factorial_sum_fourth_test != "Can't contain non-numbers") {
        echo ("Test 4 failed!");
        $result = "Tests fail!";
    }
    print($result);
}
echo ("-----------------------------<br>");
echo ("Run Homework Test Cases:<br>");
test();

?>
