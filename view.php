<?php
session_start();

if (!isset($_GET['profile_id']) ){
    $_SESSION['error'] = 'Missing profile_id';
    header("Location: index.php");
    return;
}
else
{
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc', 'nasooh', 'olabi');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    try{
        $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :p_id");
        $stmt->execute(array(
            'p_id' => $_GET['profile_id']
        ));
    }
    catch (Exception $ex){
        echo ("Exception message :". $ex->getMessage());
        return;
    }
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

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
<h1>Profile information</h1>
<p>First Name:
<?= htmlentities($row['first_name']) ?></p>
<p>Last Name:
<?= htmlentities($row['last_name']) ?></p>
<p>Email:
<?= htmlentities($row['email']) ?></p>
<p>Headline:<br/>
<?= htmlentities($row['headline']) ?></p>
<p>Summary:<br/>
<?= htmlentities($row['summary']) ?><p>
</p>
<a href="index.php">Done</a>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script></body>
</html>

