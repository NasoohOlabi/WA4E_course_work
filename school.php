<?php
    session_start();
    if (!isset($_GET['term'])){
        echo('Missing required parameter');
        return;
    }
    if (!isset($_SESSION['name'])){
        echo('ACCESS DENIED');
        return;
    }
    require_once('pdo.php');
    
    $stmt = $pdo->prepare('SELECT name FROM Institution WHERE name LIKE :prefix');
    $stmt->execute(array( ':prefix' => $_REQUEST['term']."%"));
    $retval = array();
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $retval[] = $row['name'];
    }

    header('Content-Type: application/json; charset=utf-8');
    echo(json_encode($retval));


?>