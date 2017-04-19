<?php
    require("../connectdb.php");

    if (isset($_POST["term"]) && isset($_POST["action"])) {
        $term = $_POST["term"];
        $action = $_POST["action"];

        mysqli_real_escape_string($dbc, $term);
        mysqli_real_escape_string($dbc, $action);

        $term = strip_tags($term);
        $action = strip_tags($action);

        if ($action == "delete") {
            mysqli_query($dbc, "DELETE FROM terms WHERE term = '" . $term . "';");
        }
        else if ($action == "add") {
            mysqli_query($dbc, "INSERT INTO terms (term) VALUES ('" . $term . "');");
        }
        else if ($action == "update" && isset($_POST["update"])) {
            $update = $_POST["update"];
            mysqli_query($dbc, "UPDATE terms SET term = '" . $update . "' WHERE term ='" . $term . "';"); 
        }

        $html = "";
        $q = mysqli_query($dbc, "SELECT * FROM terms;");

        if ($q) {
            while ($r = mysqli_fetch_array($q, MYSQLI_NUM)) {
                $html .= "
                    <tr>
                        <td class='term'>" . $r[0] . "</td>
                        <td><a class='btn' onclick='update(\"" . $r[0] . "\", \"update\")'>Edit</a><a class='btn delete' onclick='update(\"" . $r[0] . "\", \"delete\")'>Delete</a></td>
                    </tr>
                ";
            }
        }
        echo $html;
    }
?>