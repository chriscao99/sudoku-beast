<?php
    $lowest_hint_count = 17;
    $b = $_POST['b'];
    $c = 81 - substr_count($b, ".");
    if($c < $lowest_hint_count) {
        echo("400 Not enough hints");
        return;
    }
    $s = exec("python3 sudoku.py ".escapeshellcmd($b));
    echo($s);
?>
