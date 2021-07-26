<?php

// $errors = array();   //declare empty array to add errors too
session_start();
include "library.php";
/* FORMAT OF THE PROFILE PICTURE STORED ON LOKI : profile-pic{ID}.extension stored
   in 3420project_images folder in www_data on yusufghodiwala account  */
if(isset($_SESSION['user_id'])){
  $filename = "profile-pic" . $_SESSION['user_id'];
 $profpicpath = "/home/yusufghodiwala/public_html/www_data/3420project_images/";
  // glob function to run a search with a wildcard to return all matching filenames.

  $result = glob ($profpicpath . $filename . ".*" );
    // if array is empty, no match, else build URL.
  if(empty($result))
    $picExists = false;
  else{
    $picExists = true;
    $profpic_url = "https://loki.trentu.ca/~yusufghodiwala/www_data/3420project_images/";
    $url = explode("/",$result[sizeof($result) - 1]);
    $profpic_url = $profpic_url . $url[sizeof($url)-1]; 
  }
  
}
$stmt = array();
$keyword = $_POST['keyword'] ?? null;
$searchPref = $_POST['searchPreference'] ?? null;

if(isset($_POST['submit'])){


$pdo = connectDB();
//concatentate a % at the end of the keyword for sql searching
$keyword .="%";
  $query = "SELECT Signup_sheets.ID, Signup_sheets.Owner_ID, Signup_sheets.Title, Signup_sheets.Description FROM `Signup_sheets` LEFT JOIN users on Signup_sheets.Owner_ID=users.ID WHERE username LIKE ? OR Description LIKE ? OR TITLE LIKE ? AND SEARCHABLE=true";
  $stmt = $pdo->prepare($query);
  $stmt->execute([$keyword,$keyword,$keyword]);
  $list1 = $stmt->fetchAll();
//if rowcount is 0, set empty to true to display no results found in html
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
            <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
          </div>
          <div>
          <!-- Navbar-->
          <a href="../index.php"><li>Home</li></a> 
          <!-- if user id set in session (logged in), show the below navbar elements-->         
          <?php if(isset($_SESSION['user_id'])):?> 
            <a href="../create.php"><li>Create</li></a>
            <a href="./mystuff.php"><li>View</li></a>
            <a href="./edit_account.php"><li>My Account</li></a>
            <a href="./logout.php"><li>Logout</li></a>
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>">
            
            
            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i></li></a>
            <?php endif?>
            
          <?php endif ?>
          <!-- if user id not present in session (not logged in) , show navbar elements to sign up or login-->
          <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="./registration.php"><li>Sign-up<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
            <a href="./login.php"><li>Login<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
          <?php endif ?>

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
<!--if the results obtained from sql query are empty, show no results found -->
<?php if(isset($empty) && $empty==true):?>
  <div>
    <h2>No results found.</h2>
  </div>
<?php endif?>

<?php if(isset($empty) && $empty==false):?>
  <div>
  <h2>Found <?=$numresults?> result/results for keyword</h2>
  <table>
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                    </tr>
                </thead>
                <tbody>
                <?php if($list1==null) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                <?php if($list1!=null) : foreach ($list1 as $row): ?>
                    <tr>
                      <!--Pull Title, description. If user is logged in and owns the sheet, then redirect to viewing_owner with id passed in url, else viewing_user-->
                      <?php 
                      if(isset($_SESSION['user_id'])):
                      if($_SESSION['user_id']==$row['Owner_ID']):?>
                        <td><a href="./viewing_owner.php?SheetID=<?=$row['ID']?>"><?=$row['Title']?><i class="fas fa-link"></i></a></td>
                        <td><?=$row['Description']?></td>
                      <?php else:?>
                        <td><a href="./viewing_user.php?SheetID=<?=$row['ID']?>"><?=$row['Title']?><i class="fas fa-link"></i></a></td>
                        <td><?=$row['Description']?></td>
                      <?php endif;?>
                      <?php else:?>
                        <td><a href="./viewing_user.php?SheetID=<?=$row['ID']?>"><?=$row['Title']?><i class="fas fa-link"></i></a></td>
                        <td><?=$row['Description']?></td>
                      <?php endif;?>
                    </tr>
                    <?php endforeach; endif;endif?>

                </tbody>
            </table>
  </div>

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