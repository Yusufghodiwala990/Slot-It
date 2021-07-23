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



if (isset($_POST['delete'])) {
  $query3 = "DELETE from Signup_sheets where ID = ?";  
  $stmt3 = $pdo->prepare($query3);
  $stmt3->execute([$Sheet_ID]);
  echo "Successfully Deleted the sheet.";
  header("Location:mystuff.php");
exit;
}
if (isset($_POST['submit'])) {

  $Title = $_POST['name'] ?? null;
  $description = $_POST['description'] ?? null;
  $start = $_POST['start'] ?? null;
  $end = $_POST['end'] ?? null;
  $startTime = $_POST['start-time'];
  $endTime = $_POST['end-time'];   
  $duration = $_POST['duration'];
  $searchable = $_POST['searchability'] ?? false;

  if($searchable==true){
    $searchable=1;
  }

  else{
    $searchable=0;
  }
  

  if (!isset($Title) || strlen($Title) == 0 ) {
    $errors['name'] = true;
  }

  if (!isset($description) || strlen($description) == 0 ) {
    $errors['description'] = true;
  }

     //implement date time error later

     if(count($errors) == 0){
      $query2 = "update Signup_sheets set Title= ?, Description=?, StartDate=?, StartTime=?, EndTime=?, SlotDuration=?, searchable=? where ID=?"; 
      $stmt2 = $pdo->prepare($query2);
      $stmt2->execute([$Title,$description,$start,$startTime,$endTime,$duration,$searchable,$Sheet_ID]);
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
    <script defer src="deleteModal.js"></script>


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
          <input type="date" name="start" id="start" value="<?=$result1['StartDate']?>" autocomplete="off">
          <label for="start">Start Date</label>
        </div>

        <div>
            <input type="time" name="start-time" value="<?=$result1['StartTime']?>">
            <label for="start-time">Pick a start time:</label>
        </div>

        <div>
            <input type="time" name="end-time" value="<?=$result1['EndTime']?>">
            <label for="end-time">Pick a end time:</label>
        </div>

        <div>
          <label for="duration">Select slot duration length:</label>
          <select name="duration" id="slotDuration">
              <option value="<?="300"?>" <?php if($result1['SlotDuration']==300) echo 'selected'?> >5 mins</option>
              <option value="<?="600"?>" <?php if($result1['SlotDuration']==600) echo 'selected'?>>10 mins</option>
              <option value="<?="900"?>" <?php if($result1['SlotDuration']==900) echo 'selected'?>>15 mins</option>
              <option value="<?="1800"?>" <?php if($result1['SlotDuration']==1800) echo 'selected'?>>30 mins</option>
              <option value="<?="3600"?>" <?php if($result1['SlotDuration']==3600) echo 'selected'?>>1 hour</option>
              <option value="<?="7200"?>" <?php if($result1['SlotDuration']==7200) echo 'selected'?>>2 hours</option>
            </select>
          </div>

        <div>
            <input id="searchable" name="searchability" type="checkbox" <?php if($result1['searchable']==1) echo 'checked';?> />
            <label for="searchable">Make my sheet searchable</label> 
        </div>

        <div>
          <a href=""><button type="submit" name="submit">Submit</button></a>
        </div>


      </form>
    </section>
    
    </main>
    <!-- <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data"> -->
    <div class="deletebutton">
    <button id="delete">Delete Account</button>
  </div>
  <div id="ModalWindow" class="modal">

  <div class="content">
  <p>Are you sure you want to continue?</p>
          <div>
            <button id="no">No</button>
            <button id="yes">Yes</button>
          </div>  
  </div>

</div>
<!-- </form> -->

</body>
</html>