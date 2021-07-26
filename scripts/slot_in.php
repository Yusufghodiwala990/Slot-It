<?php
include "library.php";

session_start();
$pdo = connectDB(); //connect to database

//If the guest is logged in with google
if(isset($_SESSION['googleName'])){
$query1 = "SELECT * FROM `Guest_users` WHERE Name=? && Email=?";  //selecting the guest details
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$_SESSION['googleName'],$_SESSION['googleEmail']]);
$GuestID = $stmt1->fetch();
$_SESSION['Guest_ID']=$GuestID['ID'];  //Creating a session for the GuestID
unset($_SESSION['googleName']);
unset($_SESSION['googleEmail']);

}
//If the user is logged in to their account
if(isset($_SESSION['user_id']))
{
    $user = $_SESSION['user_id'];  //Creating a session for the UserID
    
    //If the user tries to slot in after being logged in
    if(isset($_POST['submit'])){
        $ID = explode("-", $_POST['submit']); //splitting the value attribute passed in the post array
        $sheet_id = $ID[0];
        $Slot_ID = $ID[1];
    }
    //if the user tries to slot in before being logged (redirect comes from login.php)
    else{
         $sheet_id= $_SESSION['SheetID'];
        $Slot_ID = $_SESSION['SlotID'];
        unset($_SESSION['SheetID']);
unset($_SESSION['SlotID']);
     }
//selecting the ownerID of the signup sheet
        $check= "select Owner_ID from `Signup_sheets` where ID=?"; 
        $statement = $pdo->prepare($check);
            $statement->execute([$sheet_id]);
            $checker = $statement->fetch();

            //checking if the user is the owner of the signup sheet
            if($checker['Owner_ID']==$user)
            {
                echo"You cannot slot into your own signup sheet!";
                header("Refresh:3 url=mystuff.php");
                exit();
            }
            
            //Slotting in the user to the slot
            $query = "UPDATE `Slots` SET User_ID=? WHERE Sheet_ID=? && Slot_ID=? ";
            $stmt = $pdo->prepare($query);
            $stmt->execute([$user,$sheet_id,$Slot_ID]);
            
        
 }
        
    //if the user is logged in as guest 
else{
    //if the get array for slotID is passed
    if(isset($_GET['Slot_ID'])){
    $sheet_id = $_GET['SheetID'];
    $Slot_ID = $_GET['Slot_ID'];
    }
    //if the session for slotID is set
    elseif(isset($_SESSION['SlotID'])){
        $sheet_id= $_SESSION['SheetID'];
        $Slot_ID = $_SESSION['SlotID'];
            }
        //if the session for guestID is set
        if(isset($_SESSION['Guest_ID'])){
        $GuestID=$_SESSION['Guest_ID'];
        //slotiing in the guest to the slot
    $query5 = "UPDATE `Slots` SET Guest_ID=? WHERE Sheet_ID=? && Slot_ID=? ";
    $stmt5 = $pdo->prepare($query5);
    $stmt5->execute([$GuestID,$sheet_id,$Slot_ID]);
}
unset($_SESSION['SheetID']);
unset($_SESSION['SlotID']);
unset($_SESSION['Guest_ID']);
    }
    //Adding 1 to the total number of signups
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