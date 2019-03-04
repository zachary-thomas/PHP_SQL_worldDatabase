<?php
// World Database by Zachary Thomas, index.php
session_start();
header('Content-Type: text/html');
?>

<html>
<head>
    <link rel="stylesheet" href="mystyle.css"/>
</head>
<body>

<h1>World Database</h1>
<p>By Zachary Thomas</p>
<img id="profileImg" src="64profile.jpg" alt="Zachary Thomas Pic" />

<?php
$host = "cis.gvsu.edu";
$user = "thomazac";
$pass = "thomazac";
$dbname = "thomazac";
$dbconn = new mysqli($host, $user, $pass, $dbname);

$dbconn->set_charset("utf8");

$qu = "SELECT DISTINCT Continent FROM country;"; //string
$result = $dbconn->query($qu);

if ($dbconn->connect_error) {
    die("Connection failed: " . $dbconn->connect_error);
} else {
    echo "Select a continent to view: ";

}

?>
<form action="/~thomazac/PHPhomework6/countryList.php" method="GET">
    <select name="submitted">
        <?php
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            $contString = $row["Continent"];
            printf("<option value='%s'>%s</option>", $contString, $contString);
            $count++;
        }
        ?>
    </select>
    <input type="submit" value="Submit">
</form>

</body>
</html>
