<?php

// $errors = array();   //declare empty array to add errors too
session_start();
include "library.php";

$stmt = array();
$keyword = $_POST['keyword'] ?? null;
$searchPref = $_POST['searchPreference'] ?? null;

if(isset($_POST['submit'])){


$pdo = connectDB();


  $query = "SELECT * FROM `Signup_sheets` INNER JOIN users on Signup_sheets.Owner_ID=users.ID WHERE username LIKE ? OR Description LIKE ? OR TITLE LIKE ? AND SEARCHABLE=true";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$keyword,$keyword,$keyword]);
  
  if($stmt->rowCount()==0){
    $empty = true;
  }
  else{
    $numresults = $stmt->rowCount();
    $empty = false;
  }



}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Search Sign-ups</title>
  <link rel="stylesheet" href="../styles/search.css" />
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
</head>

<body>
<header>
      <nav>
        <ul>
          <div>
            <li><img src="../img/logo.png" alt="Slot-it logo"></li>
          </div>
          <div>
          <a href="../index.html"><li>Home</li></a>
          <a href="./mystuff.php"><li>View</li></a>
          <a href="./edit_account.php"><li>My Account<i class="fa fa-user" aria-hidden="true"></i></li></a>
        </div>
        </ul>
      </nav>      
    </header>
  <main>
    <section>
      <h1> Search for Sign-ups </h1>
          <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false">
              
              <div>
              <input type="text" autocomplete="on" placeholder="Enter a keyword.." name="keyword"/>
              <button name="submit" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
              
              </div>
              
          </form>
          
<?php if(isset($empty) && $empty==true):?>
  <div>
    <h2>No results found.</h2>
  </div>
<?php endif?>

<?php if(isset($empty) && $empty==false):?>
  <div>
  <h2>Found <?=$numresults?> result/results for keyword</h2>
    <ol>
<?php foreach($stmt as $row):?>
  <?php 
if(isset($_SESSION['user_id'])):
  if($_SESSION['user_id']==$row['Owner_ID']):
    ?>
    <li><a href="./viewing_owner.php?SheetID=<?=$row['ID']?>"><?=$row['Title']?><i class="fas fa-link"></i></a></li>

  <?php else:?>
         <li><a href="./viewing_user.php?SheetID=<?=$row['ID']?>"><?=$row['Title']?><i class="fas fa-link"></i></a></li>
  <?php endif; ?>
  <?php else: ;
?>
    <li><a href="./viewing_user.php?SheetID=<?=$row['ID']?>"><?=$row['Title']?><i class="fas fa-link"></i></a></li>
  <?php endif; endforeach; ?>
          </ol>
          </div>
<?php endif?>

  </section>
  </main>
  <footer>
      <ul>
        <li><a href="../index.html">Home</a></li>
        <li><a href="mailto:slot-it@gmail.com">Contact</a></li>
        <li><i class="fas fa-phone-square-alt"></i> : +1(705)-123-1234</li>
        <li><img src="../img/logo.png" alt="Slot-it logo"></li>
      </ul>
      <p>&copy; 2021 - Slot-It - All rights reserved</p>
      
    </footer>
</body>
</html>