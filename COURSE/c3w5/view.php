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
    if ( isset($_SESSION["success"]) ) {
        echo('<p style="color:green">'.$_SESSION["success"]."</p>\n");
        unset($_SESSION["success"]);
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
<title>  724c8bee  Nasooh Olabi - Nassouh Yaser AlOlabi</title>

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" 
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" 
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" 
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>

</head>
<body>




<div class="container">
<h2>Welcome to the Automobiles Database</h2>

<h2>Automobiles</h2>

<?php 
$count = 0;
$acc = '<table border="1">
<thead><tr>
<th>Make</th>
<th>Model</th>
<th>Year</th>
<th>Mileage</th>
<th>Action</th>
</tr></thead>';
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    $count = $count + 1;
 $acc = $acc."<tr><td>".htmlentities($row['make'])."</td><td>".htmlentities($row['model'])."</td><td>".htmlentities($row['year'])."</td><td>".htmlentities($row['mileage'])."</td><td><a href=\"edit.php?autos_id=".$row['autos_id']."\">Edit</a> / <a href=\"delete.php?autos_id=".$row['autos_id']."\">Delete</a></td>"."</tr>" ;
}
if($count == 0){
    echo("<p>No rows found</p>");
}else{
    echo("Autos Found: $count \n");
    echo($acc);
}
?>
</table>
<p>
<a href="add.php">Add New Entry</a> </p>
<p>
<a href="logout.php">Logout</a>
</p>
<p>
<b>Note:</b> Your implementation should retain data across multiple 
logout/login sessions.  This sample implementation clears all its
data on logout - which you should not do in your implementation.
</p>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>
