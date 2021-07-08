<?php // Do not put any HTML above this line

if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to game.php
    header("Location: index.php");
    return;
}

function str_contains($s , $char){
    for($i=0; $i<strlen($s); $i++) 
        if ($s[$i] == $char){
            return true;
        }
    return false ;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1'; // pass is php123
#$stored_hash = 'a8609e8d62c043243c4e201cbb342862';  // pass is meow123
#$stored_hash = '5874dc20209b5ed27c0d87aba5702ae5'; // pass is umsi


$failure = false;  // If we have no POST data
session_start();

// Check to see if we have some POST data, if we do process it
if ( isset($_POST['email']) && isset($_POST['pass']) ) {
    unset($_SESSION["email"]);  // Logout current user
    if ( strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1 ) {
        $failure = "User name and password are required";
    } else {
        $check = hash('md5', $salt.$_POST['pass']);
        $flag = str_contains($_POST['email'] , "@");
        if ( $check == $stored_hash && $flag ) {
            // Redirect the browser to game.php
            /*header("Location: autos.php?name=".urlencode($_POST['email']));
            return;*/
            $_SESSION["email"] = $_POST["email"];
            $_SESSION["success"] = "Logged in.";
            header( 'Location: view.php' ) ;
            return;
        } else {
            if (!$flag){
                $failure = "Email must have an at-sign (@)";
            }else{
                $failure = "Incorrect password.";
            }
            $_SESSION["error"] = $failure;
            header( 'Location: login.php' ) ;
            return;
        }
    }
}

// Fall through into the View
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Nasooh Olabi - Nassouh Yaser AlOlabi</title>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php
// Note triple not equals and think how badly double
// not equals would work here...

if (isset($_SESSION['error'])){
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}

?>
<form method="POST">
<label for="nam">Email</label>
<input type="text" name="email" id="nam"><br/>
<label for="id_1723">Password</label>
<input type="text" name="pass" id="id_1723"><br/>
<input type="submit" value="Log In">
<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the four character sound a cat
makes (all lower case) followed by 123. -->
</p>
</div>
</body>
