<?php
session_start();
function flashThisSessionAtter ($str){
    if ( isset($_SESSION[$str]) ) {
        echo('<p style="color:red">'.$_SESSION[$str]."</p>\n");
        unset($_SESSION[$str]);
    }  
}
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

function validatePos() {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['year'.$i]) ) continue;
      if ( ! isset($_POST['desc'.$i]) ) continue;
  
      $year = $_POST['year'.$i];
      $desc = $_POST['desc'.$i];
  
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
f('first_name');
f('last_name');
f('email');
f('headline');
f('summary');
if (isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}
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
else
{
    if ( isset($_SESSION['first_name'])&&nonempty_in_array($_SESSION)&&isset($_SESSION['email'])&&isset($_SESSION['headline'])&&isset($_SESSION['summary']) && str_contains($_SESSION['email'] , "@") && validatePos()===true){
        $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try{
            // validate email
            $stmt = $pdo->prepare("INSERT INTO `profile`(`user_id`, `first_name`, `last_name`, `email`, `headline`, `summary`) VALUES ( :user_id , :fname ,:lname,:email,:hline,:sum)");
            $stmt->execute(array(
                'user_id' => $_SESSION['user_id'],
                'fname' => $_SESSION['first_name'],
                'lname' => $_SESSION['last_name'],
                'email' => $_SESSION['email'],
                'hline' => $_SESSION['headline'],
                'sum' => $_SESSION['summary'],
            ));
            
            $profile_id = $pdo->lastInsertId();
            
            $rank = 1;
            for($i=1; $i<=9; $i++) {
            if ( ! isset($_POST['year'.$i]) ) continue;
            if ( ! isset($_POST['desc'.$i]) ) continue;

            $year = $_POST['year'.$i];
            $desc = $_POST['desc'.$i];
            $stmt = $pdo->prepare('INSERT INTO Position
                (profile_id, rank, year, description)
                VALUES ( :pid, :rank, :year, :desc)');

            $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
            );

            $rank++;

            }

            $_SESSION['success'] = 'added';
            header("Location: index.php");
            return;
        }
        catch (Exception $ex){
            echo ("Exception message :". $ex->getMessage());
            return;
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
        $vp = validatePos();
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
<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<h1>Adding Profile for UMSI</h1>
<?php 
    flashThisSessionAtter('error');
?>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60"/></p>
<p>Last Name:
<input type="text" name="last_name" size="60"/></p>
<p>Email:
<input type="text" name="email" size="30"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"></textarea>

<p>Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">

</div></p>



<p>
<input type="submit" value="Add">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>


<script src="js/jquery-1.10.2.js"></script>
<script src="js/jquery-ui-1.11.4.js"></script>
<script>
countPos = 1;

// http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
$(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
        // http://api.jquery.com/event.preventdefault/
        event.preventDefault();
        if ( countPos >= 9 ) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        window.console && console.log("Adding position "+countPos);
        $('#position_fields').append(
            '<div id="position'+countPos+'"> \
            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
            <input type="button" value="-" \
            onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });
});
</script>


</div>
</body>
</html>
