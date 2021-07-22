 <?php 
include "library.php";
$pdo = connectDB();
session_start();

$Sheet_ID = $_GET['SheetID']??null;
$query1 = "select ID,Description,Title,Owner_ID from Signup_sheets where ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$Sheet_ID]);
$result = $stmt1->fetch();


$query2 = "select fname from users where ID=?"; 
$stmt2 = $pdo->prepare($query2);
$stmt2->execute([$result['Owner_ID']]);
$result2 = $stmt2->fetch();

$query3 = "select Scheduled_slots,Guest_ID,user_ID,Slot_ID from Slots where Sheet_ID=?"; 
$stmt3 = $pdo->prepare($query3);
$stmt3->execute([$Sheet_ID]);
$list1 = $stmt3->fetchAll();

?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Viewing Sign-ups</title>
  <link rel="stylesheet" href="../styles/viewing.css" />
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
          <a href="./mystuff.php"><li>View</li></a>
          <a href="derek.html"><li>My Account<i class="fa fa-user" aria-hidden="true"></i></li></a>
        </div>
        </ul>
      </nav>
</header>

<main>
<p> Title: <?=$result['Title']?></p>
<p> Owner: <?=$result2['fname']?></p>   
<form action="./slot_in.php" method="post" novalidate autocomplete="false">
    <table>
          <thead>
            <tr>
              <th scope="col">Purpose</th>
              <th scope="col">Date</th>
              <th scope="col">Time</th>
              <th scope="col">Name</th>
            </tr>
          </thead>
          <tbody>
          <?php if($list1==null) : ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <?php endif; ?>
                    <?php if($list1!=null) : foreach ($list1 as $row):?>
                    <tr>
              <td><?=$result['Description']?></td>
              <td><?=$row['Scheduled_slots']?></td>
              <td><?=$row['Scheduled_slots']?></td>
              <?php if(!isset($row['user_ID'])&&!isset($row['Guest_ID'])): ?>
                <?php if(isset($_SESSION['user_id'])):?>
             <td><button name="submit" type="submit" value="<?=$result['ID'] . "-" . $row['Slot_ID']?>">SLOT ME IN</button></td>
             <?php elseif(isset($_SESSION['Guest_ID'])): ?>   
              <td><button><a href="./slot_in.php?SheetID=<?php echo $result['ID']?>& Slot_ID=<?php echo $row['Slot_ID']?>">Slot-me-in</a></button></td>
              <?php else: ?>   
                <td><button><a href="./login.php?SheetID=<?php echo $result['ID']?>& Slot_ID=<?php echo $row['Slot_ID']?>">Slot-me-in</a></button></td>
              <?php endif; ?>
              <?php  elseif(!isset($row['Guest_ID'])): 
                $query4 = "select fname from users where ID=?"; 
                $stmt4 = $pdo->prepare($query4);
                $stmt4->execute([$row['user_ID']]);
                $result1 = $stmt4->fetch(); 
                ?>
                <td><?=$result1['fname']?></td>
              <?php else: ?>
               <?php $query4 = "select Name from Guest_users where ID=?"; 
                $stmt4 = $pdo->prepare($query4);
                $stmt4->execute([$row['Guest_ID']]);
                $result2 = $stmt4->fetch();
              ?>
              <td><?=$result2['Name']?></td>
              <?php endif ?>
              
                <?php endforeach; endif?>

          </tbody>
        </table>
</form>
</main>
<footer>
      <ul>
        <li><a href="../index.html">Home</a></li>
        <li><a href="mailto:slot-it@gmail.com">Contact</a></li>
        <li><i class="fas fa-phone-square-alt"></i> : +1(705)-123-1234</li>
        <li> <img src="../img/logo.png" alt="Slot-it logo"></li>
      </ul>
      <p>&copy; 2021 - Slot-It - All rights reserved</p>
      
    </footer>
  </body>
</html>
