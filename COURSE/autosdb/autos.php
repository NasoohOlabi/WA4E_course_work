<?php

// Demand a GET parameter
if ( ! isset($_GET['name']) || strlen($_GET['name']) < 1  ) {
    die('Name parameter missing');
}
else{
    $Da_name = $_GET['name'];
}

// If the user requested logout go back to index.php
if ( isset($_POST['logout']) ) {
    header('Location: index.php');
    return;
}
$err = false;
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if (isset($_POST['make']) || isset($_POST['year']) || isset($_POST['mileage'])  ){
    if (is_numeric ($_POST['year']) && is_numeric ($_POST['mileage'])){
        $stmt = $pdo->prepare("INSERT INTO `autos`( `make`, `year`, `mileage`) VALUES (:make,:year,:mileage)");
        $stmt->execute(array(
            ":make" => ($_POST['make']),
            ":year" => ($_POST['year']),
            ":mileage" => ($_POST['mileage'])
        ));
    }
    else{
        $err = "<p style=\"color: red;\">Mileage and year must be numeric</p>";
    }
   if (strlen($_POST['make']) == 0){
        $err = "<p style=\"color: red;\">Make is required</p>";
    }
}
try{
    $stmt = $pdo->prepare("SELECT * FROM autos ");
    $stmt->execute();
    }
    catch (Exception $ex){
        echo ("Exception message :". $ex->getMessage());
        error_log("error4.php, SQL error= ".$ex->getMessage());
        return;
    }
    
    



?>



    
<!DOCTYPE html>
<html>
<head>
<title>nasooh olabi Nasooh Olabi Nassouh Olabi nasooh al olabi - Autos Tracker</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="e89b9c80a88289c68b8785"> <?= $Da_name ?>  </a></h1>
<?= $err ?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<button type="submit" value="Add">Add</button>
<input type="submit" name="logout" value="Logout">
</form>

<h2>Automobiles</h2>
<ul>
<?php 
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
echo ("<li><!--".$row['auto_id']."-->".htmlentities($row['year'])." ".htmlentities($row['make'])." / ".htmlentities($row['mileage'])."</li>" );

}
?>

</ul>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
