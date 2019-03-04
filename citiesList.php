<?php
// World Database by Zachary Thomas, citiesList.php
session_start();
header('Content-Type: text/html');
header("Cache-Control: no-cache, must-revalidate");   // HTTP/1.1
header("Pragma: no-cache");                           // HTTP/1.0
//print_r($_SESSION);

if (isset($_GET['countryCities'])) {
    $country = urldecode($_GET['countryCities']);
    $_SESSION['country'] = $_GET['countryCities'];
} else {
    $country = $_SESSION['country'];
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

    $qu = "SELECT DISTINCT COUNT(CI.CountryCode)
           FROM country CO, city CI
           WHERE CI.CountryCode = CO.Code AND CO.Name = '$country';";
    $result = $dbconn->query($qu);
    $num = $result->fetch_array();
    printf("List of %d Cities in %s, %s ", $num[0], $country, $cont);
    ?>
</h1>

<div>
    <table class="table">
        <tr>
            <th id="header">City</th>
            <th id="header">District</th>
            <th id="header">Population</th>
        </tr>


        <?php
        $qu = "SELECT DISTINCT CI.Name, CI.District, CI.Population
                FROM city CI, country CO
                WHERE CI.CountryCode = CO.Code AND CO.Name = '$country';";

        $result = $dbconn->query($qu);

        while ($row = $result->fetch_assoc()) {
            printf("<tr> <td>%s</td><td>%s</td><td id='pop'>%s</td>  </tr>",
                $row['Name'], $row['District'], $row['Population']);
        }


        ?>
    </table>
</div>
</body>
</html>
