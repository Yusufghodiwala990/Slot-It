<?php
session_start();
var_dump($_SESSION);

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
                    <tr>
                        <td>Dentist</td>
                        <td>30</td>
                        <td>17</td>
                        <td> <a href="./viewing_owner.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-edit"> Edit</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-trash"> Delete</i></a></td>
                        <td> <a href="./copy.php"><i class="fas fa-copy"> Copy</i></a></td>
                        <td> <a href="./copy.php"><i class="fa fa-link"> CopyURL</i></a></td>

                    </tr>

                    <tr>
                        <td>Tennis</td>
                        <td>22</td>
                        <td>12</td>
                        <td> <a href="./viewing_owner.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-edit"> Edit</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-trash"> Delete</i></a></td>
                        <td> <a href="./copy.php"><i class="fas fa-copy"> Copy</i></a></td>
                        <td> <a href="./copy.php"><i class="fa fa-link"> CopyURL</i></a></td>

                    </tr>

                    <tr>
                        <td>Soccer</td>
                        <td>22</td>
                        <td>12</td>
                        <td> <a href="./viewing_owner.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-edit"> Edit</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-trash"> Delete</i></a></td>
                        <td> <a href="./copy.php"><i class="fas fa-copy"> Copy</i></a></td>
                        <td> <a href="./copy.php"><i class="fa fa-link"> CopyURL</i></a></td>
                    </tr>
                    <tr>
                        <td>Labs</td>
                        <td>22</td>
                        <td>12</td>
                        <td> <a href="./viewing_owner.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-edit"> Edit</i></a></td>
                        <td> <a href="./edit_sheet.php"><i class="fas fa-trash"> Delete</i></a></td>
                        <td> <a href="./copy.php"><i class="fas fa-copy"> Copy</i></a></td>
                        <td> <a href="./copy.php"><i class="fa fa-link"> CopyURL</i></a></td>

                    </tr>
                </tbody>
            </table>

            <h2>Slots I have signed-up for</h2>

            <table>
                <thead>
                    <tr>
                        <th scope="col">Title</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Dentist</td>
                        <td> <a href="./viewing_user.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href=""><i class="fas fa-window-close"> Cancel</i></a></td>
                    </tr>

                    <tr>
                        <td>Football</td>
                        <td> <a href="./viewing_user.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href=""><i class="fas fa-window-close"> Cancel</i></a></td>
                    </tr>

                    <tr>
                        <td>Football</td>
                        <td> <a href="./viewing_user.php" ><i class="fas fa-info-circle"> Details</i></a></td>
                        <td> <a href=""><i class="fas fa-window-close"> Cancel</i></a></td>
                    </tr>
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