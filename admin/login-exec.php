<?php
//Start session
session_start();

//Include database connection details
require_once('connect/config.php');

//Array to store validation errors
$errmsg_arr = array();

//Validation error flag
$errflag = false;

//Connect to mysqli server
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_DATABASE);
if(!$conn) {
    die('Failed to connect to server: ' . mysqli_error());
}


//Function to sanitize values received from the form. Prevents SQL injection
function clean($str) {
    global $conn;
    $str = @trim($str);
    $str = stripslashes($str);

    return mysqli_real_escape_string($conn,$str);
}

//Sanitize the POST values
$login = clean($_POST['login']);
$password = clean($_POST['password']);

//Input Validations
if($login == '') {
    $errmsg_arr[] = 'Username missing';
    $errflag = true;
}
if($password == '') {
    $errmsg_arr[] = 'Password missing';
    $errflag = true;
}

//If there are input validations, redirect back to the login form
if($errflag) {
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: login-form.php");
    exit();
}

//Create query
$qry="SELECT admin_id,username,password FROM admin WHERE username='$login' AND password='$password'";
$result=mysqli_query($conn,$qry);

//Check whether the query was successful or not
if($result) {
    if(mysqli_num_rows($result) == 1) {
        //Login Successful
        session_regenerate_id();
        $member = mysqli_fetch_assoc($result);
        $_SESSION['SESS_ADMIN_ID'] = $member['admin_id'];
        $_SESSION['SESS_ADMIN_NAME'] = $member['username'];
        session_write_close();
        header("location: foods-menu.php");
        exit();
    }else {
        //Login failed
        header("location: login-failed.php");
        exit();
    }
}else {
    die("Query failed");
}
?>