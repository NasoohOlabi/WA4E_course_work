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

    if ( isset($_SESSION["message"]) ) {
        echo('<p style="color:green">'.$_SESSION["message"]."</p>\n");
        unset($_SESSION["message"]);
    }  

    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    try{
        $stmt = $pdo->prepare("SELECT * FROM autos ");
        $stmt->execute();
        }
        catch (Exception $ex){
            echo ("Exception message :". $ex->getMessage());
            return;
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
<h1>Tracking Autos for <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="07664766"><?= $_SESSION["email"] ?></a></h1>
<h2>Automobiles</h2>
<ul>
<?php 
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
echo ("<li><!--".$row['auto_id']."-->".htmlentities($row['year'])." ".htmlentities($row['make'])." / ".htmlentities($row['mileage'])."</li>" );
}
?>
</ul>
<p>
<a href="add.php">Add New</a> |
<a href="logout.php">Logout</a>
</p>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
