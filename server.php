<?php
    $db_host = "kali.ukeme.net";
    $db_user = "root";
    $db_password = "";

    $ink = mysqli_connect($db_host, $db_user, $db_password);
    if (!$ink)
        die("Database connection failed");

    mysqli_select_db($ink, "puzzlecam");;
    if(isset($_GET["info"])) {
        $info = json_decode($_GET["info"], true);
        if (addScore($info, $ink)) {
            echo "Score inserted";
        } else {
            echo "Score insertion failed";
        }
    } else {
        $result = getAllScores($ink);
        echo json_encode($result);
    }

    function addScore($info, $ink) {
        $query = "INSERT INTO Scores (Name, Time, Difficulty) VALUES ('".$info["name"]."', ".$info["time"].", '".$info["difficulty"]."')";
        $rs = mysqli_query($ink, $query);
        if (!$rs) {
            return false;
        }
        return true;
    }

    function getAllScores($ink) {
        $easy = getScoresWithDifficulty("Easy", $ink);
        $medium = getScoresWithDifficulty("Medium", $ink);
        $hard = getScoresWithDifficulty("Hard", $ink);
        $insane = getScoresWithDifficulty("Insane", $ink);
        return array("Easy" => $easy, "Medium" => $medium, "Hard" => $hard, "Insane" => $insane);
    }
    function getScoresWithDifficulty($difficulty, $ink) {
        $query = "SELECT Name, Time FROM Scores WHERE Difficulty Like '$difficulty' ORDER BY Time";
        $rs = mysqli_query($ink, $query);
        
        $results = array();
        if (mysqli_num_rows($rs) > 0) {
            while ($row = mysqli_fetch_assoc($rs)) {
                array_push($results, $row);
            }
        }
        return $results;
    }
?>