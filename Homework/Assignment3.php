<?php
echo <<<_END
       <html><head><title>PHP Form Upload</title></head><body>
        <form method='post' action='Assignment3.php' enctype='multipart/form-data'>
                Select a text file: <input type='file' name='filename' size='10'>
                <input type='submit' value='Upload'>
        </form>
_END;
// if (isset($_FILES['filename'])) {
if ($_FILES) {
    $file_name = $_FILES['filename']['name'];
    $file_type = $_FILES['filename']['type'];

    if ($file_type == 'text/plain') { // might have to check all texts with slash like in ppt
        $ext = txt;
    }

    if ($ext) {
        $name = "file.$ext";
        move_uploaded_file($_FILES['filename']['tmp_name'], $name);
        echo "Uploaded file '$file_name' as '$name':<br>";
        $file = fopen($name, 'r') or die("File does not exist or you lack permission to open it");
        $string = fread($file, filesize($name));
        $filtered_string = str_replace(PHP_EOL, '', $string);
        if (strlen($filtered_string) != 1000){
            print("$name must contain a string of 1000 numbers");
        }
        fclose($file);
        echo $filtered_string;
    } else {
        print("$file_name is not an accepted text file");
    }
} else
    echo "No file has been uploaded";
echo "</body></html>";

?>
