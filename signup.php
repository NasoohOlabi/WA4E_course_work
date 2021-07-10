<?php
if (isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}
session_start();
require_once('util.php');
function f ($s){ 
    if ( isset($_POST[$s])){
        $_SESSION[$s] = $_POST[$s];
        unset($_POST[$s]);
    }
    else{
        unset($_SESSION[$s]);
    }
}
function nonempty_in_array($arr){
    foreach($arr as $key => $value){
        if (strlen($value)===0){
            return false;
        }
    }
    return true;
}
function validatePosIN($arr) {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($arr['year'.$i]) ) continue;
      if ( ! isset($arr['desc'.$i]) ) continue;
  
      $year = $arr['year'.$i];
      $desc = $arr['desc'.$i];
  
      if ( strlen($year) == 0 || strlen($desc) == 0 ) {
        return "All fields are required";
      }
  
      if ( ! is_numeric($year) ) {
        return "Position year must be numeric";
      }
    }
    return true;
  }
  
function str_contains($s , $char){
    for($i=0; $i<strlen($s); $i++) 
        if ($s[$i] == $char){
            return true;
        }
    return false ;
}
f('Name');
f('Password');
f('email');
if ( !isset($_SESSION['name']) ){
    echo($ACCESS_DENIED);
        return;
}
else
{
    if (
        isset($_SESSION['Name'])
        && nonempty_in_array($_SESSION)
        && str_contains($_SESSION['email'] , "@")
        && validatePosIN($_POST)===true
     ){
        require_once('pdo.php');
        try{
            $salt = 'XyZzy12*_';
            $stmt = $pdo->prepare("INSERT INTO `users`(`name`, `email`, `password`) VALUES (:n , :em , :pass)");
            $stmt->execute(array(
                'n' => $_SESSION['Name'],
                'em' => $_SESSION['email'],
                'pass'=>hash('md5', $salt.$_SESSION['Password'])
            ));
            
            $_SESSION['success'] = 'Added';
            header("Location: index.php");
            return;
        }
        catch (Exception $ex){
            echo ("Exception message :". $ex->getMessage());
            return;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
        $vp = validatePosIN($_POST);
        if ( isset($_SESSION['first_name'])||isset($_SESSION['last_name'])||isset($_SESSION['email'])||isset($_SESSION['headline'])||isset($_SESSION['summary']) ){
            if($vp!==true){
                $_SESSION['error'] = $vp;
                header("Location: add.php");
                return;
            }
            $_SESSION['error'] = 'All fields are required';
            header("Location: add.php");
            return;
        }
    }
}

?><!DOCTYPE html>
<html>
<head>

<?php
    require_once('head.php');
?>

</head>
<body>
<div class="container">
<h1>Adding Profile</h1>
<?php 
    flashSessionAttribute('error','red');
?>
<form method="post">
<style>td{padding:5px 10px}</style>
<table>
<tr>
<td>First Name:</td>
<td><input type="text" name="Name" size="30"/></td>
</tr>
<tr>
<td>Email:</td>
<td><input type="text" name="email" size="30"/></td>
</tr>
<tr>
<td>Password:</td>
<td><input type="password" name="Password" size="30"/></td>
</tr>
</table>
<br/>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>
</div>
</body>
</html>
