<?php
    $uploaddir = 'uploads/';
    $uploadfile = tempnam($uploaddir,"sudoku");
    if(!$_FILES['sudoku']['name']) { die("Bad upload"); }
    if($_FILES['sudoku']['error']) { die($_FILES['sudoku']['error']); }
    if($_FILES['sudoku']['size'] > (10240000)) { die('File larger than 10 MB'); }
    move_uploaded_file($_FILES['sudoku']['tmp_name'],$uploadfile);
    $s = shell_exec("parse-sudoku -p ".escapeshellcmd($uploadfile));
    exec("rm ".escapeshellcmd($uploadfile));
    echo($s);
?>
