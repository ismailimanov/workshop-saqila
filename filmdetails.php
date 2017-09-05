<?php
require_once("dbcon.php");
?>
<!doctype html>
<html lang="da">
    <head>
        <meta charset="UTF-8">
        <title>Untitled Document</title>
    </head>
    <body>
    <?php
    $filmid = filter_input(INPUT_GET, 'filmid', FILTER_VALIDATE_INT) or die('Fejl i filmid');

    $kategori = "";

    $sql = 'SELECT title, description, rating FROM film WHERE film_id=?';
    $stmt = $link->prepare($sql);
    $stmt->bind_param('i', $filmid);
    $stmt->execute();
    $stmt->bind_result($title, $description, $rating);

    while($stmt->fetch()){
        ?>
        <h1><?=$title?></h1>
        <p><?=$description?></p>
        <p>Rating: <?=$rating?></p>
    <?php
    }

    $catsql = 'SELECT category_id FROM film_category WHERE film_id = ?';
    $catstmt = $link->prepare($catsql);
    $catstmt->bind_param('i', $filmid);
    $catstmt->execute();
    $catstmt->bind_result($catid);

    while($catstmt->fetch()){
        $kategori .= $catid;
        ?>
    <?php
    }
    $catnavn = 'SELECT name FROM category WHERE category_id=?';
    $catnavnstmt = $link->prepare($catnavn);
    $catnavnstmt->bind_param('i', $kategori);
    $catnavnstmt->execute();
    $catnavnstmt->bind_result($katnavn);
    while($catnavnstmt->fetch()){
        ?>
        <a href="filmlist.php?categoryid=<?=$kategori?>">Klik her for at se andre film i kategorien <b><?=$katnavn?></b></a><br>
    <?php
    }

    $filmsql = "SELECT actor_id FROM film_actor WHERE film_id = ?";
    $fstmt = $link->prepare($filmsql);
    $fstmt->bind_param('i', $filmid);
    $fstmt->execute();
    $fstmt->bind_result($actorid);


    $actors = array();

    while($fstmt->fetch()){
        $actors[] = $actorid;

    }
    echo "<h2>Actors:</h2>";
    echo "<ul>";
    foreach($actors as $act){
        $hentSkuespiller = mysqli_query($link, "SELECT * FROM actor WHERE actor_id = '{$act}'");
        while($row = mysqli_fetch_array($hentSkuespiller)){
            echo "<li><a href='actordetails.php?actorid={$row["actor_id"]}'>{$row["first_name"]} {$row["last_name"]}</a></li>";
        }
    }
    echo "</ul>";
    ?>
    </body>
</html>