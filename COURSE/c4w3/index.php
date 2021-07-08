<?php
    session_start();
    $status = false;
    $logout = false;
    $loggedin = false;
    $add = false;
    $table=false;
    if ( !isset($_SESSION['name']) ){
        $status = ('<p><a href="login.php">Please log in</a></p>');
        
    }else{
        $loggedin = true;
        $logout = '<p><a href="logout.php">Logout</a></p>';
        $add = '<p><a href="add.php">Add New Entry</a></p>';
    }

    function flashThisSessionAtter ($str){
        if ( isset($_SESSION[$str]) ) {
            echo('<p style="color:green">'.$_SESSION[$str]."</p>\n");
            unset($_SESSION[$str]);
        }  
    }
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try{
        $stmt = $pdo->prepare("SELECT * FROM profile ");
        $stmt->execute();
    }
    catch (Exception $ex){
        echo ("Exception message :". $ex->getMessage());
    }
    if ( isset($_SESSION['name']) ){
        $count = 0;
        $table = '';
        $acc = '<table border="1">
        <tr>
        <th>Name</th>
        <th>Headline</th>
        <th>Action</th>
        </tr>';
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $count = $count + 1;
            $acc = $acc."<tr><td>".
            '<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a>'."</td><td>".htmlentities($row['headline'])."</td><td>".
            '<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> <a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a></td>'."</tr>" ;
        }
        if($count == 0){
            $table = false;
        }else{
            $table = $table.("Autos Found: $count \n");
            $table = $acc."</table>";
        }
    }else{
        $count = 0;
        $table = '';
        $acc = '<table border="1">
        <tr>
        <th>Name</th>
        <th>Headline</th>
        </tr>';
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $count = $count + 1;
            $acc = $acc."<tr><td>".
            '<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name']).' '.htmlentities($row['last_name']).'</a>'."</td><td>".htmlentities($row['headline'])."</td>"."</tr>" ;
        }
        if($count == 0){
            $table = false;
        }else{
            $table = $table.("Autos Found: $count \n");
            $table = $acc."</table>";
        }
    }
    
?><!DOCTYPE html>
<html>
<head>
<title> 5e680791    Nasooh Olabi - Nassouh Yaser AlOlabi s </title>
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
<h1>Nasooh Olabi - Nassouh Yaser AlOlabi</h1>
<?php flashThisSessionAtter("success");flashThisSessionAtter("error"); ?>
<?= $status ?>
<?php
if (isset($_SESSION['error'])){
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])){
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
}
?><?= $logout ?>
<?= $table ?>
<?= $add ?>
<p>
<b>Note:</b> Your implementation should retain data across multiple
logout/login sessions.  This sample implementation clears all its
data periodically - which you should not do in your implementation.
</p>
</div>
</body>
</html>