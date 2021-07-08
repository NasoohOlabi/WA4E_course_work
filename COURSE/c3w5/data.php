<?php

$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc' , 'fred' , 'zap');
$stmt = $pdo->query("SELECT name , email , password FROM users");
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
try{
$stmt = $pdo->prepare("SELECT * FROM users where user_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['user_id']));
}
catch (Exception $ex){
    echo ("Exception message :". $ex->getMessage());
    error_log("error4.php, SQL error= ".$ex->getMessage());
    return;
}
$row = $stmt->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<head><title>Nice</title></head>
<body>
<h1>MD5 Maker</h1>
<p>MD5: <?= htmlentities($md5); ?></p>

</body>
</html>
