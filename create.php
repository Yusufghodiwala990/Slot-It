<?php 
  session_start();
  include "scripts/library.php";
  if(!isset($_SESSION['user_id']))
  {
    header("Location: scripts/login.php");
  }

  $errors=array();

  $userid=$_SESSION['user_id'];
  if(isset($_POST['submit']))
  {
    $title=$_POST['name'];
    $description=$_POST['description'];
    $startDate=$_POST['start-date'];
    $searchable = $_POST['searchability'] ?? false;
    $startTime = $_POST['start-time'];
    $endTime = $_POST['end-time'];   
    $duration = $_POST['duration'];

    if($searchable==true){
      $searchable=1;
    }

    else{
      $searchable=0;
    }
    
    if($_POST['name']==null)
    {
      $errors['titleError']=true;
    }
    
    if($_POST['description']==null)
    {
      $errors['descriptionError']=true;
    }

    $date  = date('Y-m-d', strtotime($startDate)); // convert date to sql supported format
 
   //implement date time error later
    if($_POST['name']!=null && $_POST['description']!=null){
    $pdo = connectDB();
   
    $query = "INSERT INTO Signup_sheets (Title,Description,Owner_ID,Date_created,No_of_signups,StartDate,StartTime,EndTime,SlotDuration,searchable) values (?,?,?,NOW(),0,?,?,?,?,?)"; 
    $stmt = $pdo->prepare($query);
    $stmt->execute([$title,$description,$userid,$date,$startTime,$endTime,$duration,$searchable]);

    $Sheet_ID = $pdo->lastInsertId(); //https://www.php.net/manual/en/pdo.lastinsertid.php

    $intervals = array(); // an array to store all the time values
    //convert start and times to seconds for unix conversion..
    $startTime = strtotime($startTime);
    $endTime = strtotime($endTime); 

    $intervals = array(); // an array to store all the time values

    // loop to start from the startTime and run until the endTime (end time is > in seconds)
    // then time forwards by the duration specified and inserted into the array in the format mySQL likes
    for($time = $startTime; $time<=$endTime; $time+=$duration){
        $intervals[] = date('H:i:s',$time);
    }

    foreach($intervals as $sTime)
    {
      $query = "INSERT INTO Slots(StartTime,Sheet_ID) values (?,?)"; 
      $stmt = $pdo->prepare($query);
      $stmt->execute([$sTime,$Sheet_ID]);
    }
    
    header("refresh:0; url=scripts/mystuff.php");
    }
  }

?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slot-It</title>
    <link rel="stylesheet" href="styles/create.css"/>
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  </head>

<body>

    <header>
        <nav>
          <ul>
            <div>
            <a href="index.html"><li>Home</li></a>
            <a href="create.html"><li>Create</li></a>
            
            <a href="./scripts/mystuff.php"><li>View</li></a>
            <a href="./scripts/login.php"><li>Login<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
            <a href="derek.html"><li>My Account<i class="fa fa-user" aria-hidden="true"></i></li></a>
          </div>
          </ul>
        </nav>      
      </header>

      <main>
        <section>
        <h1>Create a sign-up sheet</h1>
        <form id="createSheet" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
          
          <div>
            <label for="name">Sheet Name:</label>
            <input id="name" name="name" type="text" placeholder="Enter your sheet name here">
            <span class="<?=!isset($errors['titleError']) ? 'hidden' : "error";?>">Please Enter a title.</span>
          </div>

          <div>
            <label for="description">Description</label>
            <textarea name="description"  id="description" cols="30" rows="10"></textarea>
            <span class="<?=!isset($errors['descriptionError']) ? 'hidden' : "error";?>">Please set a description.</span>
          </div>
          
          <div>
            <label for="usrName">Your Name:</label>
            <input id="usrName" name="usrName" type="text" placeholder="Enter your name here">
          </div>


          <div>
            <label for="start-date">Pick a date:</label>
            <input type="date" id="start-date" name="start-date">
          </div>

          <div>
            <label for="start-time">Pick a start time:</label>
            <input type="time" name="start-time" value="13:00">
          </div>

          <div>
            <label for="end-time">Pick a end time:</label>
            <input type="time" name="end-time" value="16:00">
          </div>

          <div>
          <label for="duration">Select slot duration length:</label>
          <select name="duration" id="slotDuration">
              <option value="<?="300"?>">5 mins</option>
              <option value="<?="600"?>">10 mins</option>
              <option value="<?="900"?>">15 mins</option>
              <option value="<?="1800"?>">30 mins</option>
              <option value="<?="3600"?>">1 hour</option>
              <option value="<?="7200"?>">2 hours</option>
            </select>
          </div>

          <div>
              <input id="searchable" name="searchability" type="checkbox"/>
              <label for="searchable">Make my sheet searchable</label>
          </div>

          <div>
            <button id="submit" name="submit">Next</button>
          </div>

        </form>

        </section>
      </main>

      <footer>
      <ul>
        <li><a href="">Home</a></li>
        <li><a href="mailto:slot-it@gmail.com">Contact</a></li>
        <li><i class="fas fa-phone-square-alt"></i> : +1(705)-123-1234</li>
        <li> <img src="img/logo.png" alt="Slot-it logo"></li>
      </ul>
      <p>&copy; 2021 - Slot-It - All rights reserved</p>
      
    </footer>
    
</body>