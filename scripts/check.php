<?php 
/* This php script is used by a Javascript file on registration and edit_account
to check for username availability */
include 'library.php';

// username would have been sent from the javascript file upon losing focus
if(isset($_POST['username'])){
    $pdo = connectDB();

    // count the number of users who have the the username entered by the user
    $query = "SELECT COUNT(*) as numusers from `users` where username=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_POST['username']]);
    $row = $stmt->fetch();

    // if the count is not 0(not unique), 
    // send reponse to the javascript that initiated this php script as false.
    // else true
    if ($row['numusers'] !== 0) {
      echo "false";
    }
    else{
        echo "true";
    }

}

?>