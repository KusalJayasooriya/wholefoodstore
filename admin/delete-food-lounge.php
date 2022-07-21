<?php
//Start session
session_start();

//checking connection and connecting to a database
require_once('connect/config.php');
//Connect to mysqli server
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_DATABASE);
if(!$conn) {
    die('Failed to connect to server: ' . mysqli_error());
}


// check if the 'id' variable is set in URL
if (isset($_GET['id']))
{
    // get id value
    $id = $_GET['id'];

    // delete the entry
    $result = mysqli_query($conn,"DELETE FROM food_details_lounge WHERE food_id='$id'")
    or die("There was a problem while removing the food ... \n" . mysqli_error());

    // redirect back to the Groceries page
    header("Location: foods-lounge.php");
}
else
    // if id isn't set, redirect back to the foods page
{
    header("Location: foods-lounge.php");
}

?>