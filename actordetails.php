<?php
require_once("dbcon.php");
?>
<!doctype html>
<html lang="da">
    <head>
        <title>actors</title>
        <meta charset="utf-8">
    </head>
    <body>
    <?php
        $actorid = filter_input(INPUT_GET, 'actorid', FILTER_VALIDATE_INT);

        $sql = "SELECT first_name, last_name FROM actor WHERE actor_id = ?";
        $stmt = $link->prepare($sql);
        $stmt->bind_param('i', $actorid);
        $stmt->execute();
        $stmt->bind_result($firstname, $lastname);
        while($stmt->fetch()){
            echo "<h1>" . $firstname . " " . $lastname . "</h1>";
        }

    $filmsql = "SELECT film_id FROM film_actor WHERE actor_id = ?";
    $fstmt = $link->prepare($filmsql);
    $fstmt->bind_param('i', $actorid);
    $fstmt->execute();
    $fstmt->bind_result($filmid);


    $film = array();

    while($fstmt->fetch()){
        $film[] = $filmid;

    }
    echo "<h2>Film:</h2>";
    echo "<ul>";
    foreach($film as $fil){
        $hentFilm = mysqli_query($link, "SELECT * FROM film WHERE film_id = '{$fil}'");
        while($row = mysqli_fetch_array($hentFilm)){
            echo "<li><a href='filmdetails.php?filmid={$row["film_id"]}'>{$row["title"]}</a></li>";
        }
    }
    echo "</ul>";
    ?>
    </body>
</html>