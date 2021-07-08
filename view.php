<?php
session_start();
function flashThisSessionAtter ($str){
    if ( isset($_SESSION[$str]) ) {
        echo('<p style="color:red">'.$_SESSION[$str]."</p>\n");
        unset($_SESSION[$str]);
    }  
}
if (!isset($_GET['profile_id']) ){
    $_SESSION['error'] = 'Missing profile_id';
    header("Location: index.php");
    return;
}
else
{
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try{
        $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :p_id");
        $stmt->execute(array(
            'p_id' => $_GET['profile_id']
        ));
    }
    catch (Exception $ex){
        echo ("Exception message :". $ex->getMessage());
        return;
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

}
?><!DOCTYPE html>
<html>
<head>
<title> 5e680791     Nasooh Olabi - Nassouh Yaser AlOlabi's Profile View</title>
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
<h1>Profile information</h1>
<p>First Name:
<?= htmlentities($row['first_name']) ?></p>
<p>Last Name:
<?= htmlentities($row['last_name']) ?></p>
<p>Email:
<?= htmlentities($row['email']) ?></p>
<p>Headline:<br/>
<?= htmlentities($row['headline']) ?></p>
<p>Summary:<br/>
<?= htmlentities($row['summary']) ?><p>
</p>
<a href="index.php">Done</a>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>

