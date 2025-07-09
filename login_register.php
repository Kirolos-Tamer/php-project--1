<?php
session_start();
require_once 'config.php';
if(isset($_POST['register'])) {
    $firstname = $_POST['firstname'];
    $secondname = $_POST['lastname'];
    $username = $_POST['username'];
    $phonenumber = $_POST['phonenumber'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
    $checkUserName = $conn->query("SELECT username FROM users WHERE username = '$username'");
    $checkPhoneNumber = $conn->query("SELECT phonenumber FROM users WHERE phonenumber = '$phonenumber'");
    $checkEmail = $conn->query("SELECT email FROM users WHERE email = '$email'");
    if (!$checkEmail) {
        die("Query failed: " . $conn->error);
    }


    
    if($checkEmail->num_rows > 0) {
        $_SESSION['register_error'] = 'Email is already registered';
        $_SESSION['active_form'] = 'register';
}else if($checkUserName->num_rows > 0) {
        $_SESSION['username_error'] = 'Username is already taken';
        $_SESSION['active_form'] = 'register';
}else if($checkPhoneNumber->num_rows > 0) {
        $_SESSION['phonenumber_error'] = 'Phone Number is already registered';
        $_SESSION['active_form'] = 'register';
}else{
    $conn->query("INSERT INTO users (firstname, lastname, username, phonenumber, email, password) VALUES ('$firstname','$secondname','$username','$phonenumber','$email','$password')");
}
header("Location: index.php");
exit();
}


if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email = '$email'");
    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if(password_verify($password, $user["password"])) {
            $_SESSION["firstname"] = $user["firstname"];
            $_SESSION["secondname"] = $user["secondname"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["phonenumber"] = $user["phonenumber"];
            $_SESSION["email"] = $user["email"];
            header("Location: home.php");
            exit();
    }
}
$_SESSION['login_error'] = 'Incorrect email or password';
$_SESSION['active_form'] = 'login';
header("Location: index.php");
exit();
}

?>