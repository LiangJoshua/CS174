<?php
echo <<<_END
       <html><head><title>PHP Form Upload</title></head><body>
        <form method='post' action='Midterm1.php' enctype='multipart/form-data'>
                Select a text file with exactly and only 400 numbers: <input type='file' name='filename' size='10'>
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
            $filtered_string = str_replace(array(
                "\n",
                "\r"
            ), '', $string);
            if (strlen($filtered_string) != 400) {
                unlink($name);
                echo "$name has been discarded because it didn't contain exactly 400 numbers<br>";
            } else if (! ctype_digit($filtered_string)) {
                unlink($name);
                echo "$name has been discarded because it contains some non-number characters<br>";
            } else {
                $result = maxProductOfFour($filtered_string);
                echo "Find the greatest product of four adjacent numbers in all the four possible directions (up, down, left, right, or diagonally):<br>Four adjacent numbers:$result[1]<br>Product: $result[0]<br><br>";
            }
        } else {
            print("$file_name is not an accepted text file\n");
        }
    } else {
        print("Please select a file");
    }
} else
    echo "No file has been uploaded<br>";
echo "</body></html>";

function maxProductOfFour($string)
{
    $filtered_string = str_replace(array(
        "\n",
        "\r"
    ), '', $string);
    if (strlen($filtered_string) != 400) {
        return "ERROR: File must contain exactly 400 numbers";
    }
    $numbers = array();
    $filtered_string_index = 0;
    for ($row = 0; $row < 20; $row ++) {
        for ($col = 0; $col < 20; $col ++) {
            $numbers[$row][$col] = $filtered_string[$filtered_string_index];
            $filtered_string_index ++;
        }
    }

    $adjacent_numbers = "";
    $max_adjacent_numbers = "";
    $product = 1;
    $max_product = 1;

    // checks horizontally
    for ($row = 0; $row < 20; $row ++) {
        for ($col = 0; $col < 16; $col ++) {
            for ($i = $col; $i < $col + 4; $i ++) {
                if (! ctype_digit($numbers[$row][$i])) {
                    $max_product = "ERROR: File cannot contain non-numbers";
                    $max_adjacent_numbers = "ERROR: File cannot contain non-numbers";
                    $result = array(
                        $max_product,
                        $max_adjacent_numbers
                    );
                    return $result;
                }
                $adjacent_numbers = $adjacent_numbers . $numbers[$row][$i];
                $product = $product * intval($numbers[$row][$i]);
            }
            if ($product > $max_product) {
                $max_product = $product;
                $max_adjacent_numbers = $adjacent_numbers;
            }
            $product = 1;
            $adjacent_numbers = "";
        }
    }

    // checks vertically
    for ($col = 0; $col < 20; $col ++) {
        for ($row = 0; $row < 16; $row ++) {
            for ($i = $row; $i < $row + 4; $i ++) {
                if (! ctype_digit($numbers[$i][$col])) {
                    $max_product = "ERROR: File cannot contain non-numbers";
                    $max_adjacent_numbers = "ERROR: File cannot contain non-numbers";
                    $result = array(
                        $max_product,
                        $max_adjacent_numbers
                    );
                    return $result;
                }
                $adjacent_numbers = $adjacent_numbers . $numbers[$i][$col];
                $product = $product * intval($numbers[$i][$row]);
            }
            if ($product > $max_product) {
                $max_product = $product;
                $max_adjacent_numbers = $adjacent_numbers;
            }
            $product = 1;
            $adjacent_numbers = "";
        }
    }

    // checks diagonally down-right and up-left
    for ($row = 0; $row < 17; $row ++) { // iterates each row from top to bottom
        for ($col = 16; $col >= 0; $col --) { // iterates each column from right to left
            $diagonal_row = $row;
            for ($diagonal_col = $col; $diagonal_col < 17; $diagonal_col ++) { // iterates the diagonal
                $diagonal_index = 0;
                if (intval($numbers[$diagonal_row + 3][$diagonal_col + 3]) == null) { // checks for null pointer across diagonal
                    break;
                }
                for ($j = $diagonal_col; $j < $diagonal_col + 4; $j ++) {
                    if (! ctype_digit($numbers[$diagonal_row + $diagonal_index][$j])) {
                        $max_product = "ERROR: File cannot contain non-numbers";
                        $max_adjacent_numbers = "ERROR: File cannot contain non-numbers";
                        $result = array(
                            $max_product,
                            $max_adjacent_numbers
                        );
                        return $result;
                    }
                    $adjacent_numbers = $adjacent_numbers . $numbers[$diagonal_row + $diagonal_index][$j];
                    $product = $product * intval($numbers[$diagonal_row + $diagonal_index][$j]);
                    $diagonal_index ++;
                }
                if ($product > $max_product) {
                    $max_product = $product;
                    $max_adjacent_numbers = $adjacent_numbers;
                }

                $adjacent_numbers = "";
                $product = 1;
                $diagonal_row ++;
            }
        }
    }

    // checks diagonally up-right and down-left
    for ($row = 19; $row > 2; $row --) { // iterates each row from bottom to top
        for ($col = 16; $col >= 0; $col --) { // iterates each column from right to left
            $diagonal_row = $row;
            for ($diagonal_col = $col; $diagonal_col < 17; $diagonal_col ++) { // iterates the diagonal
                $diagonal_index = 0;
                if (intval($numbers[$diagonal_row - 3][$diagonal_col + 3]) == null) { // checks for null pointer across diagonal
                    break;
                }
                for ($j = $diagonal_col; $j < $diagonal_col + 4; $j ++) {
                    if (! ctype_digit($numbers[$diagonal_row - $diagonal_index][$j])) {
                        $max_product = "ERROR: File cannot contain non-numbers";
                        $max_adjacent_numbers = "ERROR: File cannot contain non-numbers";
                        $result = array(
                            $max_product,
                            $max_adjacent_numbers
                        );
                        return $result;
                    }
                    $adjacent_numbers = $adjacent_numbers . $numbers[$diagonal_row - $diagonal_index][$j];
                    $product = $product * intval($numbers[$diagonal_row - $diagonal_index][$j]);
                    $diagonal_index ++;
                }
                if ($product > $max_product) {
                    $max_product = $product;
                    $max_adjacent_numbers = $adjacent_numbers;
                }
                // echo ("row: $row<br>");
                // echo ("col: $col<br>");
                // echo ("$adjacent_numbers<br>");
                // echo ("$product<br>");
                // echo ("$max_product<br>");
                // echo ("$max_adjacent_numbers<br><br>");
                $adjacent_numbers = "";
                $product = 1;
                $diagonal_row --;
            }
        }
    }

    $result = array(
        $max_product,
        $max_adjacent_numbers
    );
    return $result;
}

function test()
{
    $first_test = "71636269561882670428
85861560789112949495
65727333001053367881
52584907711670556013
53697817977846174064
83972241375657056057
82166370484403199890
96983520312774506326
12540698747158523863
66896648950445244523
05886116467109405077
16427171479924442928
17866458359124566529
24219022671055626321
07198403850962455444
84580156166097919133
62229893423380308135
73167176531330624919
30358907296290491560
70172427121883998797";
    $first_test_result = maxProductOfFour($first_test);

    $second_test = "9598250477552350012268680192181756
122827529178152234387579892679221849389646356991299649
036403084071163602968042826189789475151098172996631886
160787871714515777526472011786159038414214691256694946
690802210903547835518041668155804769939470293089007140
705514552128652444980917680683749856834548337728098283
508922974216172712308358769280833080305581202683515659
136511510353453782666353745869257743028498";
    $second_test_result = maxProductOfFour($second_test);

    $third_test = "1232103901293102";
    $third_test_result = maxProductOfFour($third_test);

    $fourth_test = "71636269561882670428
85861560789112949495
65727333001053367881
52584907711670556013
53697817977846174064
83972241375657056057
82166370484403199890
96983520312774506326
12540698747158523863
668966489504452zz523
05886116467109405077
16427171479924442928
17866458359124566529
24219022671055626321
07198403850962455444
84580156166097919133
62229893423380308135
73167176531330624919
30358907296290491560
70172427121883998797";
    $fourth_test_result = maxProductOfFour($fourth_test);
    
    $fifth_test = "";
    $fifth_test_result = maxProductOfFour($fifth_test);

    $result = "Tests pass!<br>";

    if ($first_test_result[0] != 5832 || $first_test_result[1] != 9989) {
        echo ("Test 1 failed<br>");
        $result = "Tests failed!<br>";
    }
    if ($second_test_result[0] != 5832 || $second_test_result[1] != 9899) {
        echo ("Test 2 failed!<br>");
        $result = "Tests failed!<br>";
    }
    if ($third_test_result != "ERROR: File must contain exactly 400 numbers") {
        echo ("Test 3 failed!<br>");
        $result = "Tests fail!<br>";
    }
    if ($fourth_test_result[0] != "ERROR: File cannot contain non-numbers" ||
        $fourth_test_result[1] != "ERROR: File cannot contain non-numbers") {
        echo ("Test 4 failed!<br>");
        $result = "Tests failed!<br>";
    }
    if ($fifth_test_result != "ERROR: File must contain exactly 400 numbers") {
        echo ("Test 5 failed!<br>");
        $result = "Tests fail!<br>";
    }
    echo ("-----------------------------<br>");
    echo ("Run Homework Test Cases:<br>");
    print($result);
}
test();
?>
