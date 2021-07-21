<?php 
include 'library.php';
    // check email and username availability
if(isset($_POST['username'])){
    $pdo = connectDB();
    $query = "SELECT COUNT(*) as numusers from `users` where username=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_POST['username']]);
    $row = $stmt->fetch();

    if ($row['numusers'] !== 0) {
      echo "false";
    }
    else{
        echo "true";
    }

}

?>