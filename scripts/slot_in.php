<?php
include "library.php";

session_start();
$pdo = connectDB();
$errors = array();
if(isset($_SESSION['user_id']))
{
  
    $ID = explode("-", $_POST['submit']);
  
    $user = $_SESSION['user_id'];
    $sheet_id = $ID[0];
    $Slot_ID = $ID[1];

    $query = "UPDATE `Slots` SET User_ID=? WHERE Sheet_ID=? && Slot_ID=? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user,$sheet_id,$Slot_ID]);

    $query1 = "SELECT No_of_signups from `Signup_sheets` where ID=? ";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute([$sheet_id]);
    $result = $stmt1->fetch();
    $No_of_slots = $result['No_of_signups'] + 1;

    $query2 = "UPDATE `Signup_sheets` SET No_of_signups=? WHERE ID=?";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute([$No_of_slots,$sheet_id]);

    header("Refresh:0 url=mystuff.php");}

        ?>