<?php
require_once "pdo.php";
session_start();

if ( isset($_POST['make']) && isset($_POST['model'])
     && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['autos_id']) ) {

    // Data validation
    if ( strlen($_POST['make']) < 1 || strlen($_POST['year']) < 1) {
        $_SESSION['error'] = 'Missing data';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }
/*
    if ( strpos($_POST['model'],'@') === false ) {
        $_SESSION['error'] = 'Bad data';
        header("Location: edit.php?autos_id=".$_POST['autos_id']);
        return;
    }
*/
    $sql = "UPDATE autos SET make = :make,
            model = :model, year = :year,mileage = :mileage
            WHERE autos_id = :autos_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':make' => $_POST['make'],
        ':model' => $_POST['model'],
        ':year' => $_POST['year'],
        ':mileage' => $_POST['mileage'],
        ':autos_id' => $_POST['autos_id']));
    $_SESSION['success'] = 'Record updated';
    header( 'Location: view.php' ) ;
    return;
}

// Guardian: Make sure that autos_id is present
if ( ! isset($_GET['autos_id']) ) {
  $_SESSION['error'] = "Missing autos_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM autos where autos_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['autos_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for autos_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$n = htmlentities($row['make']);
$e = htmlentities($row['model']);
$p = htmlentities($row['year']);
$m = htmlentities($row['mileage']);
$autos_id = $row['autos_id'];
?>
<!DOCTYPE html>
<html>
<head><title>  724c8bee   Nasooh Olabi</title></head>
<body>
<p>Edit User</p>
<form method="post">
<p>make:
<input type="text" name="make" value="<?= $n ?>"></p>
<p>model:
<input type="text" name="model" value="<?= $e ?>"></p>
<p>year:
<input type="text" name="year" value="<?= $p ?>"></p>
<p>mileage:
<input type="text" name="mileage" value="<?= $m ?>"></p>
<input type="hidden" name="autos_id" value="<?= $autos_id ?>">
<p>
<button type="submit" value="Update">Save</button>
<a href="index.php">Cancel</a></p>
</form>
</body>
</html>