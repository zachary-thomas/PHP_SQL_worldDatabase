<?php
// World Database by Zachary Thomas, updatePop.php
session_start();
header('Content-Type: text/html');

$updatedPop = $_POST['newPop'];

if (isset($_GET['countrySel'])) {
    $country = urldecode($_GET['countrySel']);
    $_SESSION['countrySel'] = $_GET['countrySel'];
} else {
    $country = $_SESSION['countrySel'];
}

// Get continent
$cont = $_SESSION['cont'];
?>

<html>
<head>
    <link rel="stylesheet" href="mystyle.css"/>
</head>
<body>
<h1>World Database</h1>
<h1>
    <?php
    $host = "cis.gvsu.edu";
    $user = "thomazac";
    $pass = "thomazac";
    $dbname = "thomazac";
    $dbconn = new mysqli($host, $user, $pass, $dbname);

    $dbconn->set_charset("utf8");

    printf("Edit Population of %s, %s", $country, $cont);
    ?>
</h1>

<form action="" method="POST">
    <p>
        <?php
            $qu = "SELECT Population
               FROM country
               WHERE '$country' = Name;";

            $result = $dbconn->query($qu);
            $num = $result->fetch_array();

        if (isset($_POST['newPop'])) {
            $update = "UPDATE country SET Population = '$updatedPop' WHERE Name = '$country'";
            $dbconn->query($update);
            printf("<p>Population updated to: %s</p>", $updatedPop);
            // clear array
            $_POST = array();
            printf("<button type=\"button\" onclick=\"goBack()\">Ok</button>");

        } else {
            printf("Please enter the new population: ");
            printf("<input type=\"text\" name=\"newPop\" value=\"%d\">  ", $num[0]);
            printf("<input type=\"submit\" value=\"Submit\">");
        }

        $qu = "SELECT Population
               FROM country
               WHERE '$country' = Name;";

        $result = $dbconn->query($qu);
        //$num = $result->fetch_array();


        ?>
<script>
    function goBack() {
        javascript:history.go(-2);
    }
</script>

    </p>
</form>
</body>
</html>