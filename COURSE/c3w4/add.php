<?php
    session_start();

    if ( !isset($_SESSION['email']) ){
        echo("<!DOCTYPE html>
        <html>
        <head>
        <title>Nasooh Olabi - Nassouh Yaser AlOlabi</title>
        </head>
        <body>
        Not logged in
        </body>
        </html>");
        return;
    }

// Demand a GET parameter
if ( ! isset($_SESSION['email']) || strlen($_SESSION['email']) < 1  ) {
    die('Name parameter missing');
}
else{
    $Da_name = $_SESSION['email'];
}

// If the user requested Cancel go back to index.php
if ( isset($_POST['Cancel']) ) {
    header('Location: view.php');
    return;
}
$message = false;
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset ($_POST['make'])){
    $_SESSION['make'] = $_POST['make'];
}else{
    unset($_SESSION['make']);
}
if (isset ($_POST['year'])){
    $_SESSION['year'] = $_POST['year'];
}else{
    unset($_SESSION['year']);
}
if (isset ($_POST['mileage'])){
    $_SESSION['mileage'] = $_POST['mileage'];
}else{
    unset($_SESSION['mileage']);
}

if (  isset($_SESSION['make']) || isset($_SESSION['year']) || isset($_SESSION['mileage'])  ) {

    if (is_numeric ($_SESSION['year']) && is_numeric ($_SESSION['mileage']) && strlen($_SESSION['make'])>0 ){
        $stmt = $pdo->prepare("INSERT INTO `autos`( `make`, `year`, `mileage`) VALUES (:make,:year,:mileage)");
        $stmt->execute(array(
            ":make" => ($_SESSION['make']),
            ":year" => ($_SESSION['year']),
            ":mileage" => ($_SESSION['mileage'])
        ));
        $message = "<p style=\"color: green;\" >Record inserted</p>";
        $_SESSION['message'] = $message;
        header('Location: view.php');
        return;
    }
    if(   !is_numeric ($_SESSION['mileage'])|| !is_numeric($_SESSION['year'])  ){
        $message = "<p style=\"color: red;\">Mileage and year must be numeric</p>";
        $_SESSION['message'] = $message;
        header('Location: add.php');
        return;
    }
   if (strlen($_SESSION['make']) == 0){
        $message = "<p style=\"color: red;\">Make is required</p>";
        $_SESSION['message'] = $message;
        header('Location: add.php');
        return;
    }
}

?>



    
<!DOCTYPE html>
<html>
<head>
<title>Nasooh Olabi - Nassouh Yaser AlOlabi</title>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

</head>
<body>
<div class="container">
<h1>Tracking Autos for <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="e89b9c80a88289c68b8785"> <?= $Da_name ?>  </a></h1>
<?php
if(isset($_SESSION['message'])){
    echo ($_SESSION['message']);
    unset($_SESSION['message']);
}?>
<form method="post">
<p>Make:
<input type="text" name="make" size="60"/></p>
<p>Year:
<input type="text" name="year"/></p>
<p>Mileage:
<input type="text" name="mileage"/></p>
<button type="submit" value="Add">Add</button>
<input type="submit" name="Cancel" value="Cancel">
</form>
<!--
<h2>Automobiles</h2>
<ul>
<?php
/* 
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
echo ("<li><!--".$row['auto_id']."-->".htmlentities($row['year'])." ".htmlentities($row['make'])." / ".htmlentities($row['mileage'])."</li>" );

}*/
?>-->

</ul>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
