<?php
include "library.php";

session_start();
$pdo = connectDB();
$errors = array();
if(isset($_SESSION['user_id']))
{
    $user = $_SESSION['user_id'];
    $sheet_id = $_GET['SheetID'];
    $Slot_ID = $_GET['Slot_ID'];

    $query = "UPDATE `Slots` SET User_ID=? WHERE Sheet_ID=? && Slot_ID=? ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user,$sheet_id,$Slot_ID]);

    $query1 = "SELECT No_of_signups from `Signup_sheets` where ID=? ";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute([$sheet_id]);
    $result = $stmt1->fetch();
    $No_of_slots = $result['No_of_signups'] + 1;

    $query2 = "UPDATE `Signup_sheets` SET No_of_signups=? WHERE ID=?";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute([$No_of_slots,$sheet_id]);

    header("Refresh:0 url=mystuff.php");}



/*
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
              <td><a href="slot_in.php?SheetID=<?php echo $result['ID']?>& Slot_ID=<?php echo $row['Slot_ID']?>">Slot-me-in</a></td>
              <?php else: ?>   
                <td><a href="guestlogin.php?SheetID=<?php echo $result['ID']?>& Slot_ID=<?php echo $row['Slot_ID']?>">Slot-me-in</a></td>
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
        </table>*/
        ?>