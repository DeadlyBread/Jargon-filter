<?php
    require("../connectdb.php");

    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $username = $_POST["username"];
        $password = sha1($_POST["password"]);

        mysqli_real_escape_string($dbc, $username);
        mysqli_real_escape_string($dbc, $password);

        $username = strip_tags($username);
        $password = strip_tags($password);

        $q = mysqli_query($dbc, "SELECT username FROM user WHERE username = '" . $username . "' AND password_sha1 = '" . $password . "';");

        if ($q) {
            $r = mysqli_fetch_array($q, MYSQLI_NUM);
            if (!$r) {
                header("Location: index.html?e=incorrect");
            }
        }
    }
    else {
        header("Location: index.html?e=empty");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Oakhall Jargon Filter — Edit</title>

	<link href="style.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
</head>
<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
        function update(t = null, a = null, u = null) {
            if (a == "update") {
                var replace = prompt("What would you like to replace '" + t + "' with? (Please make sure the new term is all lower case and only contains spaces, no hyphens)");
                if (replace == null || replace == "") return;
                else u = replace;
            }
            else if (a == "add") {
                t = prompt("What term would you like to add? (Please make sure the term is all lower case and only contains spaces, no hyphens)");
            }
            else if (a == "delete") {
                if (!confirm("Are you sure you want to delete the term '" + t + "'?")) {
                    return;
                }
            }

            $.ajax({
                url: "update.php",
                type: "post",
                data: {
                    term: t,
                    action: a,
                    update: u
                },
                success: function(r) {
                    document.getElementById("terms").innerHTML = r;
                }
            });
        }
        $(update);
    </script>
    <div id="container">
        <h1>Oakhall Jargon Filter — Edit terms</h1>
        <table id="terms">
            <a class="btn" id="add" onclick="update(null, 'add')">+ Add new term</a>
        </table>
    </div>
</body>
</html>