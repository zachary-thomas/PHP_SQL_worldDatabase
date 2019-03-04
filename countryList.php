<?php
// World Database by Zachary Thomas, countryList.php
session_start();

// Use to get previous submitted to sort
if(isset($_GET['submitted'])){
    $cont = urldecode($_GET['submitted']);
    $_SESSION['cont'] = $_GET['submitted'];
}
else{
    $cont = $_SESSION['cont'];
}

if(isset($_GET['sort'])){
    $sort = urldecode($_GET['sort']);
    $_SESSION['sort'] = $_GET['sort'];
} else {
    $sort = $_SESSION['sort'];
}
?>

<html>
<head>
    <link rel="stylesheet" href="mystyle.css"/>
</head>

<h1>World Database</h1>
    <h1>
        <?php
        $host = "cis.gvsu.edu";
        $user = "thomazac";
        $pass = "thomazac";
        $dbname = "thomazac";
        $dbconn = new mysqli($host, $user, $pass, $dbname);

        $dbconn->set_charset("utf8");

        $qu = "SELECT COUNT(Name) FROM country WHERE Continent = '$cont';";
        $result = $dbconn->query($qu);
        $num = $result->fetch_array();
        printf("%s with %d Countries", $cont, $num[0]);
        ?>
    </h1>

    <form action="" method="GET">
        <p>Sort By:
            <input type="radio" name="sort" value="COName"> Country Name
            <input type="radio" name="sort" value="CO.Population"> Population
            <input type="submit" value="Sort">
        </p>
    </form>

    <div>
        <table class="table">
            <tr>
                <th>Country Name</th>
                <th>Capital Name</th>
                <th>Head of State</th>
                <th>Population</th>
                <th>Language(s) Spoken</th>
            </tr>

            <?php
            // Show table

            // Sort by query
            if(isset($sort)) {
                $qu = "SELECT DISTINCT CO.Name AS COName, CI.Name, CO.HeadOfState, CO.Population
            FROM city CI, country CO
            WHERE CI.CountryCode = CO.Code AND CO.Continent = '$cont'
                   AND CO.Capital = CI.ID
             ORDER BY $sort;";
            } else{
                $qu = "SELECT DISTINCT CO.Name AS COName, CI.Name, CO.HeadOfState, CO.Population
            FROM city CI, country CO
            WHERE CI.CountryCode = CO.Code AND CO.Continent = '$cont'
                   AND CO.Capital = CI.ID;";
            }

            $result = $dbconn->query($qu);
            while ($row = $result->fetch_assoc()) {
                //print_r($row);
                $conName = $row['COName'];
                // Top 5 lang spoken query
                $spoken = "SELECT CL.Language, CL.Percentage, CL.IsOfficial
                FROM countrylanguage CL, country CO
                WHERE '$conName'= CO.Name AND CO.Code = CL.CountryCode
                ORDER BY CL.Percentage DESC LIMIT 5;";

                $resultLangs = $dbconn->query($spoken);
                $langsCompact = "";
                $langsCompactArr = [];
                $i = 0;

                while ($langs = $resultLangs->fetch_assoc()){
                    if($langs['IsOfficial'] == 'T') {
                        $langsCompactArr[$i] = sprintf("<p id=\"officialLang\">%s (%s%%) </p>", $langs['Language'], $langs['Percentage']);

                    } else {
                        $langsCompactArr[$i] = sprintf("<p>%s (%s%%) </p>", $langs['Language'], $langs['Percentage']);
                    }

                    $i++;
                }

                $langsCompact = join($langsCompactArr);

                // Print main table
                printf("<tr><td> <a href= 'citiesList.php?countryCities=%s'> %s</a> </td><td>%s</td><td>%s</td>
                                <td id='pop'>  <a href='updatePop.php?countrySel=%s'> %s </a>   </td> <td >    %s</td> </tr>",
                    $row['COName'], $row['COName'], $row['Name'], $row['HeadOfState'],$row['COName'], $row['Population'], $langsCompact);

            }

            ?>
        </table>
    </div>

    </body>
</html>