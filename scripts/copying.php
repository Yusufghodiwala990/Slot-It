<?php
include "library.php";
session_start();
$pdo = connectDB(); //Connect to database
//Redirect if not logged in.
if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit;
}

$Sheet_ID = $_GET['SheetID'];  //Getting the sheetID passed

$query1 = "select * from Signup_sheets where ID=?"; //Selecting all details of the signup sheet
$stmt1 = $pdo->prepare($query1);  
$stmt1->execute([$Sheet_ID]);
$result1 = $stmt1->fetch();

//inserting the details selected into the signup sheet table
$query2 = "insert into Signup_sheets (Title,Description,Owner_ID,No_of_slots,No_of_signups,StartDate,StartTime,EndTime,SlotDuration,searchable) values (?,?,?,?,'0',?,?,?,?,?)"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$result1['Title'],$result1['Description'],$result1['Owner_ID'],$result1['No_of_slots'],$result1['StartDate'],$result1['StartTime'],$result1['EndTime'],$result1['SlotDuration'],$result1['searchable']]);
$Sheet_ID = $pdo->lastInsertId();
//redirecting to edit sheet to allow edit details of the inserted sheet
header("location:edit_sheet.php?SheetID=$Sheet_ID"); 
exit();
?>