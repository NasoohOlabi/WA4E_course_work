<?php
require_once "pdo.php";
session_start();

if ( !isset($_SESSION['name']) ){
    echo("<!DOCTYPE html>
        <html>
        <head>
        <title> 5e680791    Nasooh Olabi - Nassouh Yaser AlOlabi</title>
        </head>
        <body>
        ACCESS DENIED
        </body>
        </html>");
        return;
}

if (isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}


if ( isset($_POST['delete']) && isset($_GET['profile_id']) ) {
    $sql = "DELETE FROM profile WHERE profile_id = :zip";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('zip' => $_GET['profile_id']));
    $_SESSION['success'] = 'Record deleted';
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: Make sure that profile_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT `first_name` , `last_name`, `profile_id` FROM profile where profile_id = :xyz");
$stmt->execute(array("xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

?><!DOCTYPE html>
<html>
<head>
<title> 5e680791     Nasooh Olabi - Nassouh Yaser AlOlabi's Profile Add</title>
<!-- bootstrap.php - this is HTML -->

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Deleteing Profile</h1>
<form method="post" >
<p>First Name:
<?= htmlentities($row['first_name'])?></p>
<p>Last Name:
<?= htmlentities($row['last_name'])?></p>
<input type="hidden" name="profile_id"
value="<?= $row['profile_id']?>"
/>
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
