<?php
include "library.php";

session_start();
$pdo = connectDB();
$errors = array();

if(isset($_SESSION['googleName'])){
$query1 = "SELECT * FROM `Guest_users` WHERE Name=? && Email=?";
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$_SESSION['googleName'],$_SESSION['googleEmail']]);
$GuestID = $stmt1->fetch();
$_SESSION['Guest_ID']=$GuestID['ID'];
}

if(isset($_SESSION['user_id']))
{
    $user = $_SESSION['user_id'];
    if(isset($_POST['submit'])){
        $ID = explode("-", $_POST['submit']);
        $sheet_id = $ID[0];
        $Slot_ID = $ID[1];
            }
            else{
                $sheet_id= $_SESSION['SheetID'];
                $Slot_ID = $_SESSION['SlotID'];
            }

        $check= "select Owner_ID from `Signup_sheets` where ID=?";
        $statement = $pdo->prepare($check);
            $statement->execute([$sheet_id]);
            $checker = $statement->fetch();

            if($checker['Owner_ID']==$user)
            {
                echo"You cannot slot into your own signup sheet!";
                header("Refresh:3 url=mystuff.php");
                exit();
            }
            

            $query = "UPDATE `Slots` SET User_ID=? WHERE Sheet_ID=? && Slot_ID=? ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user,$sheet_id,$Slot_ID]);
        
            }
        
    
else{

    if(isset($_GET['Slot_ID'])){
    $sheet_id = $_GET['SheetID'];
    $Slot_ID = $_GET['Slot_ID'];
    }

    elseif(isset($_SESSION['SlotID'])){
        $sheet_id= $_SESSION['SheetID'];
        $Slot_ID = $_SESSION['SlotID'];
            }

    if(isset($_SESSION['Guest_ID'])){
        $GuestID=$_SESSION['Guest_ID'];
    $query5 = "UPDATE `Slots` SET Guest_ID=? WHERE Sheet_ID=? && Slot_ID=? ";
    $stmt5 = $pdo->prepare($query5);
    $stmt5->execute([$GuestID,$sheet_id,$Slot_ID]);
}
    }
    
    $query1 = "SELECT No_of_signups from `Signup_sheets` where ID=? ";
            $stmt1 = $pdo->prepare($query1);
            $stmt1->execute([$sheet_id]);
            $result = $stmt1->fetch();
            $No_of_slots = $result['No_of_signups'] + 1;
        
            $query2 = "UPDATE `Signup_sheets` SET No_of_signups=? WHERE ID=?";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->execute([$No_of_slots,$sheet_id]);
        
            header("Location:viewing_user.php?SheetID=$sheet_id");

        ?>