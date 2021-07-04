<?php

// $errors = array();   //declare empty array to add errors too

include "library.php";

$empty = true;
$stmt = array();
$keyword = $_POST['keyword'] ?? null;
if(isset($_POST['submit'])){

 $keyword .="%";
  echo $keyword;

$pdo = connectDB();
$query = "SELECT * FROM `Signup_sheets` WHERE TITLE LIKE ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$keyword]);

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
          <a href="../create.html"><li>Create</li></a>          
          <a href="./viewing.php"><li>View</li></a>
          <a href="./edit_account.php"><li>My Account<i class="fa fa-user" aria-hidden="true"></i></li></a>
        </div>
        </ul>
      </nav>      
    </header>
  <main>
      <h1> Search for Sign-ups </h1>
          <form  <form action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate autocomplete="false">
              <input type="text" autocomplete="on" placeholder="Title..." name="keyword"/>
              <button name="submit" type="submit"><i class="fa fa-search" aria-hidden="true"></i></button></>
          </form>
          

         
<?php if(!$empty):?> 
  <h2>Found <?=$numresults?> result/results for keyword </h2>
  <div>
<?php foreach($stmt as $row):?>
          
          <a href="./viewing_user.php?SheetID=<?=$row['ID']?>"><?=$row['Title']?><i class="fas fa-link"></i></a>
          <?php endforeach; ?>
          </div>
<?php endif?>



 
     
          
          
          <!-- <table>
          <thead>
            <tr>
              <th></th>
              <th scope="col">Title</th>
              <th scope="col">Date</th>
              <th scope="col">Time</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Patient1 appointment</td>
              <td>7th July 2021</td>
              <td>12:30pm</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Patient2 appointment</td>
              <td>8th august 2021</td>
              <td>8:00am</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>Patient3 appointment</td>
              <td>2nd December 2021</td>
              <td>10:00am</td>
            </tr>

            <tr>
              <th scope="row">4</th>
              <td>Patient4 appointment</td>
              <td>20th December 2021</td>
              <td>2:00pm</td>
            </tr>
          </tbody>
        </table> -->
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