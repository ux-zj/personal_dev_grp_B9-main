<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="../CSS/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a5e8e2f662.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<?php
session_start();
require_once "Components/navbar.php";
require_once "../Config/DatabaseManager.php";
require_once "../Api/Objects/User.php";

$logg = $_GET['logg'];
//echo $logg;

if ($logg == "out"){
	session_unset();
	header("location: home.php?alert=signed_out");
	exit;

} else if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: home.php");
  exit;
  
} else if (isset($_POST["name"]) && isset($_POST["password"])) {

  $usernameGiven = $_POST["name"];
  $passwordGiven = $_POST["password"];

    if ($logg != "out"){

        $database = new DatabaseManager();
        $userFound = $database->getUser($_POST["password"], $_POST["name"]);

        if ($userFound != null && ($userFound->getUsername() === $usernameGiven && $userFound->getPassword() === $passwordGiven)){

            $_SESSION["loggedin"] = true;
            $_SESSION["userID"] = $userFound->getId();
            header("location: home.php?alert=success");

        } else {
            $_SESSION["loggedin"] = false;		//if wrong credentials
            echo "failed";
            header("Location: login.php?logg=in");
            exit();
        }

        $database->close();
    }
}

echo <<<_END
    <body>
        <div class="container py-4">
            <div class="row g-0 border rounded bg-light overflow-hidden flex-md-row mb-4 shadow-sm h-md-250" style="border-radius: 15px !important;">
                <div class="col p-4 d-flex flex-column position-static">
                    <form action="login.php" method="post">
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input name="name" type="text" class="form-control" id="name" placeholder="username">
                        </div>
                        <div class="form-group mt-4">
                            <label for="password">Password</label>
                            <input name="password" type="password" class="form-control" id="password" placeholder="password">
                        </div>
                        <button type="submit" class="btn btn-primary mt-4">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
    </html>
_END;

exit();

?>


