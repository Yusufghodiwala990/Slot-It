<?php 
session_start();
include "library.php";

$pdo = connectDB();
$errors = array();

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

$Title = $_POST['name'] ?? null;
$description = $_POST['description'] ?? null;
$start = $_POST['start'] ?? null;
$end = $_POST['end'] ?? null;
$slots = $_POST['slots'] ?? null;
$added_slots = $slots + $result1['No_of_slots'];

if (isset($_POST['delete'])) {
  $query3 = "DELETE from Signup_sheets where ID = ?";  
  $stmt3 = $pdo->prepare($query3);
  $stmt3->execute([$Sheet_ID]);
  echo "Successfully Deleted the sheet.";
  header("Location:mystuff.php");
exit;
}
if (isset($_POST['submit'])) {

  if (!isset($Title) || strlen($Title) == 0 ) {
    $errors['name'] = true;
  }

  if (!isset($description) || strlen($description) == 0 ) {
    $errors['description'] = true;
  }

  if(!is_numeric($added_slots))
  {
    $errors['slots']=true;
  }
     //implement date time error later

     if(count($errors) == 0){
      $query2 = "update Signup_sheets set Title= ?, Description=?, start=?, End=?, No_of_slots=? where ID=?"; 
      $stmt2 = $pdo->prepare($query2);
      $stmt2->execute([$Title,$description,$result1['StartDate'],$added_slots,$Sheet_ID]);
      echo "bye!";
      header("Location:mystuff.php");
      exit;
     }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sheet</title>
    <link rel="stylesheet" href="../styles/edit_sheet.css"/>
    <link rel="stylesheet" href="../styles/errors.css" />

    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>

</head>
<body>
<header>
      <nav>
        <ul>
          <div>
            <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
          </div>
          <div>
          <a href="../index.html"><li>Home</li></a>
          <a href="../create.html"><li>Create</li></a>
          <a href="./mystuff.php"><li>View</li></a>
          <a href="./login.php"><li>Login <i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
          <a href="./edit_account.php"><li>My Account <i class="fa fa-user" aria-hidden="true"></i></li></a>
        </div>
        </ul>
      </nav>      
    </header>

    <main>
    <section>
        <h2>Edit Sheet</h2>
        <form method="POST" novalidate autocomplete="false" enctype="multipart/form-data">
        <div>
          <input type="text" name="name" autocomplete="off"value="<?=$result1['Title']?>" id="name">
          <label for="name">Sheet Name</label>
          <span class="error <?=!isset($errors['name']) ? 'hidden' : ""; ?>">Please enter a Title</span>

        </div>

        <div>
            <label for="description" id="special">Description</label>
            <textarea name="description"  id="description" cols="84" rows="5" autocomplete="off"><?=$result1['Description']?></textarea>
            <span class="<?=!isset($errors['description']) ? 'hidden' : "error";?>">Please set a description.</span>
          </div>


          <div>
          <input type="date" name="start" id="start" value="<?=$result1['StartDate']?>" autocomplete="off" readonly='readonly'>
          <label for="start">Start Date</label>
        </div>

        <div>
          <input type="date" name="end" id="end" autocomplete="off" value="<?=$result1['End']?>" >
          <label for="end">End Date</label>
        </div>

        <div>
          <input type="number" name="slots" id="slots" value="<?=$result1['No_of_slots']?>" autocomplete="off">
          <label for="slots">Add Slots</label>
          <span class="<?=!isset($errors['slots']) ? 'hidden' : "error";?>">Please enter a numeric number of slots</span>
        </div>

        <div>
          <a href=""><button type="button">Remove Slots</button></a>    
        </div>

        <div>
          <a href=""><button type="submit" name="submit">Submit</button></a>
        </div>

        <div class="deletebutton">
      <button type="submit" name="delete">Delete Sheet</button>
      </div>
      </form>
    </section>
    
    </main>
</body>
</html>