<?php
    require("connectdb.php");

    if (isset($_POST["text"])) {
        $text = $_POST["text"];

        $text = mysqli_real_escape_string($dbc, $text);
        $text = strip_tags($text);
        $text_lower = strtolower($text);

        $q = mysqli_query($dbc, "select * from terms where '" . $text_lower . "' LIKE CONCAT('%', term,'%');");
        if ($q) {
            $count = 0;

            while ($r = mysqli_fetch_array($q, MYSQLI_NUM)) {
                $text = str_ireplace($r[0], "blah", $text);
                $count++;
            }
            echo $text . " (jargon count: " . $count . ")";
        }
    }
?>