<?php

// $errors = array();   //declare empty array to add errors too
session_start();
$user=$_SESSION['user_id'];   //need this from yusuf's page
include '../Includes/library.php';
// CONNECT TO DATABASE
$pdo = connectDB();
$query = "select * from Signup_sheets where user_ID=?"; 
$stmt = $pdo->prepare($query);
$stmt->execute([$user]);
$list1 = $stmt->fetchAll();

$query1 = "select Signup_sheets.Title, Slots.Scheduled_slots from Signup_sheets INNER JOIN slots where user_ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$user]);
$lists2 = $stmt1->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My-View</title>
    <link rel="stylesheet" href="../styles/mystuff.css" />
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
                    <a href="./search.php">
                        <li>Search</li>
                    </a>
                    <a href="../create.html">
                        <li>Create</li>
                    </a>

                    <a href="./edit_account.php">
                        <li>My Account<i class="fa fa-user" aria-hidden="true"></i></li>
                    </a>
                </div>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>My Sign-up sheets</h2>

            <table>
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Number of slots</th>
                        <th scope="col">Total sign-ups</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($list1 as $row): ?>
                    <tr>
                        <td><?=$row['Title']?></td>
                        <td><?=$row['No_of_slots']?></td>
                        <td><?=$row['no_of_signups']?></td>
                        <td> <a href="./viewing_owner.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-edit"> Edit</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-trash"> Delete</i></a></td>
                        <td> <a href="./copy.php"><i class="fas fa-copy"> Copy</i></a></td>
                        <td> <a href="./copy.php"><i class="fa fa-link"> CopyURL</i></a></td>
                    </tr>
                    <?php endforeach;?>

                </tbody>
            </table>

            <h2>Slots I have signed-up for</h2>

            <table>
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                        <td>Date</td>
                        <td>Time</td>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($list2 as $row): ?>

                    <tr>
                        <td><?=$row['Title']?></td>
                        <td><?=$row['Scheduled_slot']?></td>
                        <td><?=$row['Scheduled_slot']?></td>

                        <td> <a href="./viewing_user.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href=""><i class="fas fa-window-close"> Cancel</i></a></td>
                    </tr>
                    <?php endforeach;?>

                </tbody>
            </table>
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