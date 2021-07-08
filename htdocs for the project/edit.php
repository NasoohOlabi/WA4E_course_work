<?php
session_start();
function str_contains($s , $char){
    for($i=0; $i<strlen($s); $i++) 
        if ($s[$i] == $char){
            return true;
        }
    return false ;
}
function flashThisSessionAtter ($str){
    if ( isset($_SESSION[$str]) ) {
        echo('<p style="color:red">'.$_SESSION[$str]."</p>\n");
        unset($_SESSION[$str]);
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

if (isset($_POST['cancel'])){
    header("Location: index.php");
    return;
}
if (!isset($_GET['profile_id']) && isset($_SESSION['name'])){
    $_SESSION['error'] = 'Missing profile_id';
    header("Location: index.php");
    return;
}
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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
    if ( isset($_SESSION['name'])&&isset($_GET['profile_id'])&&isset($_POST['first_name'])&&isset($_POST['last_name'])&&nonempty_in_array($_POST)&&nonempty_in_array(array('first_name','last_name','email','headline','summary'))&&isset($_POST['summary']) && str_contains($_POST['email'] , "@")&& validatePos()===true ){
        
        try{
            // validate email
            $sql = "UPDATE profile SET first_name = :fn,
                    last_name = :ln, email = :e ,headline = :hl , summary = :sum
                    WHERE profile_id = :profile_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':e' => $_POST['email'],
                ':hl' => $_POST['headline'],
                ':sum' => $_POST['summary'],
                'profile_id' => $_GET['profile_id']
            ));
            $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
            $stmt->execute(array( ':pid' => $_GET['profile_id']));
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
                ':pid' => $_GET['profile_id'],
                ':rank' => $rank,
                ':year' => $year,
                ':desc' => $desc)
                );

                $rank++;
            }

            $_SESSION['success'] = 'Profile updated';
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
        if ( 
            isset($_POST['first_name'])
            ||isset($_POST['last_name'])
            ||isset($_POST['email'])
            ||isset($_POST['headline'])
            ||isset($_POST['summary'])
        ){
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

<?php require_once('head.php');?>

</head>
<body>
<?php
flashThisSessionAtter ('error');
$stmt = $pdo->prepare("SELECT `first_name` , `last_name` , email,headline,summary, `profile_id` FROM profile where profile_id = :xyz");
$stmt->execute(array("xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$f = htmlentities($row['first_name']);
$l = htmlentities($row['last_name']);
$e = htmlentities($row['email']);
$h = htmlentities($row['headline']);
$s = htmlentities($row['summary']);



?>
<div class="container">
<h1>Editing Profile for UMSI</h1>
<form method="post">
<p>First Name:
<input type="text" name="first_name" size="60" value="<?= $f ?>" /></p>
<p>Last Name:
<input type="text" name="last_name" size="60" value="<?= $l ?>"/></p>
<p>Email:
<input type="text" name="email" size="30" value="<?= $e ?>"/></p>
<p>Headline:<br/>
<input type="text" name="headline" size="80" value="<?= $h ?>"/></p>
<p>Summary:<br/>
<textarea name="summary" rows="8" cols="80"><?= $s ?></textarea>

<p>
Education: <input type="submit" id="addEdu" value="+">
<div id="edu_fields">
<?php 
$stmt = $pdo->prepare("SELECT * FROM  `education` AS e JOIN `institution` AS i where profile_id = :xyz AND i.institution_id = e.institution_id ORDER BY rank");
$stmt->execute(array("xyz" => $_GET['profile_id']));
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo('<div id="edu'.$row['rank'].'"><p>Year: <input type="text" name="edu_year'.$row['rank'].'" value="'.htmlentities($row['year']).'" />
    <input type="button" value="-" onclick="$(\'#edu'.$row['rank'].'\').remove();return false;"></p>
    <p>School: <input type="text" size="80" name="edu_school'.$row['rank'].'" class="school"
    value="'.htmlentities($row['name']).'" />
    </div>');
}
?>
</div>
</p>

<p>Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
<?php 
$stmt = $pdo->prepare("SELECT rank , year , description FROM  `position` where profile_id = :xyz ORDER BY rank");
$stmt->execute(array("xyz" => $_GET['profile_id']));
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
    echo('<div id="position'.$row['rank'].'">
    <p>Year: <input type="text" name="year'.$row['rank'].'" value="'.htmlentities($row['year']).'" />
    <input type="button" value="-" onclick="$(\'#position1\').remove();return false;">
    </p>
    <textarea name="desc'.$row['rank'].'" rows="8" cols="80">'.htmlentities($row['description']).'</textarea>
    </div>');
}
?>
</div></p>
<p>
<input type="submit" value="Save">
<input type="submit" name="cancel" value="Cancel">
</p>
</form>


<script>
countPos = 0;
countEdu = 0;

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
            <input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove();return false;"><br>\
            <textarea name="desc'+countPos+'" rows="8" cols="80"></textarea>\
            </div>');
    });

    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        window.console && console.log("Adding education "+countEdu);

        $('#edu_fields').append(
            '<div id="edu'+countEdu+'"> \
            <p>Year: <input type="text" name="edu_year'+countEdu+'" value="" /> \
            <input type="button" value="-" onclick="$(\'#edu'+countEdu+'\').remove();return false;"><br>\
            <p>School: <input type="text" size="80" name="edu_school'+countEdu+'" class="school" value="" />\
            </p></div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });

    });

});

</script>


</div>
</body>
</html>
