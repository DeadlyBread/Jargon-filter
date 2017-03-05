<?php
    require("connectdb.php");   //Connect to database

    if (isset($_POST["text"])) {
        $text = $_POST["text"];

        //Sanitise text
        $text = mysqli_real_escape_string($dbc, $text);
        $text = strip_tags($text);

        $text_lower = strtolower($text);
        $text_lower = str_replace("-", " ", $text_lower);

        $q = mysqli_query($dbc, "select * from terms where '" . $text_lower . "' LIKE CONCAT('%', term,'%');"); //Query database to return jargon terms that text contains
        if ($q) {
            $count_lower = 0;
            $count_capital = 0;

            while ($r = mysqli_fetch_array($q, MYSQLI_NUM)) {
                $text = preg_replace("/[A-Z](?i)(" . substr_replace($r[0], "", 0, 1) . "|" . substr_replace(str_replace(" ", "-", $r[0]), "", 0, 1) . ")\w*/", "Blah", $text, -1, $count_capital);
                $text = preg_replace("/(" . $r[0] . "|" . str_replace(" ", "-", $r[0]) . ")\w*/i", "blah", $text, -1, $count_lower);
            }

            $count = $count_lower + $count_capital;
            echo $text . " (jargon count: " . $count . ") [jargon score: " . score($count, $text) . "/100] << 0 - jargon free | 25 - some jargon | 50 - getting dangerous | 75 - guff city >>";
        }
    }

    function score($b, $t) {
        $s = 1000 * $b / str_word_count($t);

        if ($s > 100) $s = 100;
        else if ($s < 0) $s = 0;

        return $s;
    }
?>