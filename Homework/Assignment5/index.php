<?php
require_once 'login.php';

echo <<<_END
       <html><head><title>PHP Form Upload</title></head><body>
        <form method='post' action='index.php' enctype='multipart/form-data'>
                Select a text file: <input type="file" name="content" size='10'><br>
                Type in the name of the document: <input type="text" name="name" placeholder="Name"><br>
                <input type="hidden" name="upload" value="yes">
                <input type="submit" value="submit" name="submit">
        </form>
_END;

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error)
    die(mysql_fatal_error("mysql connect error"));

if (isset($_POST["submit"])) {
    $name = get_post($conn, 'name');
    $file_name = $_FILES['content']['name'];
    $file_type = $_FILES['content']['type'];

    if ($file_type == 'text/plain') {
        $ext = 'txt';
    }
    if ($file_name != '' && $name != '') {
        if (isset($ext)) {
            $new_name = $name . '.' . $ext;
            $uploads_dir = $_SERVER['DOCUMENT_ROOT'] . '/Assignment5/uploads/' . $new_name;
            move_uploaded_file($_FILES['content']['tmp_name'], "$uploads_dir");
            echo "Uploaded file $file_name as $name<br><br>";
            $file = fopen($uploads_dir, 'r') or die("File does not exist or you lack permission to open it");
            $string = fread($file, filesize($uploads_dir));
            $insert_query = "INSERT INTO text_files (name, content) VALUES ('$name', '$string')";
            $insert_result = $conn->query($insert_query);
            if (! $insert_result)
                echo "INSERT failed: $insert_query<br>" . $conn->error . "<br><br>";
            fclose($file);
        } else {
            print("$file_name is not an accepted text file<br><br>");
        }
    } else {
        print("Please select a file and enter name of the document<br><br>");
    }
}

$select_query = "SELECT * FROM text_files";
$select_result = $conn->query($select_query);
if (! $select_result)
    die("select query error");
$rows = $select_result->num_rows;
echo "DATABASE CONTENT<br>";
echo "--------------------------------------------------------------------------------------------------------------<br>";
for ($j = 0; $j < $rows; ++ $j) {
    $select_result->data_seek($j);
    $row = $select_result->fetch_array(MYSQLI_ASSOC);
    echo 'Name: ' . $row['name'] . '<br>';
    echo 'Content: ' . $row['content'] . '<br><br>';
}
$select_result->close();
$conn->close();

function get_post($conn, $var)
{
    return $conn->real_escape_string($_POST[$var]);
}

function mysql_fatal_error($msg)
{
    echo <<< _END
We are sorry, but it was not possible to complete
the requested task. 
<p>$msg</p>
_END;
}
?>