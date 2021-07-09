<?php
include "library.php";
session_start();
$pdo = connectDB();

if(!isset($_SESSION['user_id']))
{
  header("location:login.php");
  exit;
}

$Sheet_ID = $_GET['SheetID'];

$query1 = "select * from Signup_sheets where ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$Sheet_ID]);
$result1 = $stmt1->fetch();

$query2 = "insert into Signup_sheets (Title,Description,Owner_ID,No_of_slots,No_of_signups,Start,End) values (?,?,?,?,'0',?,?)"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$result1['Title'],$result1['Description'],$result1['Owner_ID'],$result1['No_of_slots'],$result1['Start'],$result1['End']]);
$result2 = $stmt1->fetch();

header("location:edit_sheet.php?SheetID=$Sheet_ID");
?>