<?php 
  session_start();
  if(isset($_SESSION['user_id'])){
    /* FORMAT OF THE PROFILE PICTURE STORED ON LOKI : profile-pic{ID}.extension stored
   in 3420project_images folder in www_data on yusufghodiwala account  */
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
  include "scripts/library.php";
  if(!isset($_SESSION['user_id']))
  {
    header("Location: scripts/login.php");
  }

  $errors=array();


  $userid=$_SESSION['user_id'];
  if(isset($_POST['submit']))
  { 

    
    $pdo = connectDB();
    
    //pull values from form
    $title=$_POST['name'];
    $description=$_POST['description'];
    $startDate=$_POST['start-date'];
    $searchable = $_POST['searchability'] ?? false;
    $startTime = $_POST['start-time'];
    $endTime = $_POST['end-time'];   
    $duration = $_POST['duration'];


    $startInSeconds = strtotime($startTime);
    $endInSeconds = strtotime($endTime);


    $date_now = new DateTime();
$date_entered = new DateTime($startDate);
if($date_entered < $date_now){
  $errors['invalid_date'] = true;
}

    //convert searchable to 0/1 values for sql insertion
    if($searchable==true){
      $searchable=1;
    }

    else{
      $searchable=0;
    }


    // validation for title and desc
  if (!isset($title) || strlen($title) == 0) {
    $errors['title'] = true;
  }

  if (!isset($description) || strlen($description) == 0) {
    $errors['desc'] = true;
  }


  // checking if start and end time are valid
  if($startInSeconds > $endInSeconds){
    $errors['invalid_time'] = true;
  }


  // checking if start or end time are empty
  if($startTime==''){
      $errors['start'] = true;
  }

  if($endTime==''){
    $errors['end'] = true;
  }


  if($startDate==""){
    $errors['date'] = true;

  }
  


    

    $date  = date('Y-m-d', strtotime($startDate)); // convert date to sql supported format
 
  
  // validation for title and desc
  if (!isset($title) || strlen($title=== 0)) {
    $errors['title'] = true;
  }

  if (!isset($description) || strlen($description) == 0) {
    $errors['desc'] = true;
  }


  // checking if start and end time are valid
  if($startInSeconds > $endInSeconds){
    $errors['invalid_time'] = true;
  }


  // checking if start or end time are empty
  if($startTime==''){
      $errors['start'] = true;
  }

  if($endTime==''){
    $errors['end'] = true;
  }

  
  if($duration > ($endInSeconds - $startInSeconds)){
    $errors['invalid_duration'] = true;
  }
  

    if(count($errors)=== 0){
    $intervals = array(); // an array to store all the time values
    //convert start and times to seconds for unix conversion..
    $startTimeSecs = strtotime($startTime);
    $endTimeSecs = strtotime($endTime); 

    $intervals = array(); // an array to store all the time values

    // loop to start from the startTime and run until the endTime (end time is > in seconds)
    // then time forwards by the duration specified and inserted into the array in the format mySQL likes
    for($time = $startTimeSecs; $time<=$endTimeSecs; $time+=$duration){
        $intervals[] = date('H:i:s',$time);
    }

    $noOfSlots = count($intervals);
    $query = "INSERT INTO Signup_sheets (Title,Description,Owner_ID,Date_created,No_of_slots,No_of_signups,StartDate,StartTime,EndTime,SlotDuration,searchable) values (?,?,?,NOW(),?,0,?,?,?,?,?)"; 
    $stmt = $pdo->prepare($query);
    $stmt->execute([$title,$description,$userid,$noOfSlots,$date,$startTime,$endTime,$duration,$searchable]);

    $Sheet_ID = $pdo->lastInsertId(); //https://www.php.net/manual/en/pdo.lastinsertid.php

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
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slot-It</title>
    <link rel="stylesheet" href="styles/create.css"/>
    <script defer src="scripts/create.js"></script>
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  </head>

<body>

    <header>
        <nav>
          <ul>
            <div>
            <a href="index.php"><li>Home</li></a>
          <a href="./scripts/search.php"><li>Search</li></a>
          
          <?php if(isset($_SESSION['user_id'])):?>
            <a href="./scripts/mystuff.php"><li>View</li></a>
            <a href="./scripts/edit_account.php"><li>My Account</li></a>
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>" alt="Profile Picture">
            
            
            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i></li></a>
            <?php endif?>
            
          <?php endif ?>
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
            <span class="hidden error">Please Enter a title.</span>
            <span class="error <?= !isset($errors['title']) ? 'hidden' : ""; ?>">Please enter a title </span>
          </div>

          <div>
            <label for="description">Description</label>
            <textarea name="description"  id="description" cols="30" rows="10"></textarea>
            <span class="hidden error">Please set a description.</span>
            <span class="error <?= !isset($errors['desc']) ? 'hidden' : ""; ?>">Please enter a description </span>
          </div>
        
          <div>
            <label for="start-date">Pick a date:</label>
            <input type="date" id="start-date" name="start-date">
            <span class="hidden error">Start date cannot be before today's date.</span>
            <span class="hidden error">Please set a start date.</span>
            <span class="error <?= !isset($errors['date']) ? 'hidden' : ""; ?>">Please enter a start date </span>
            <span class="error <?= !isset($errors['invalid_date']) ? 'hidden' : ""; ?>">Start date cannot be before today's date </span>
          </div>

          <div>
            <label for="start-time">Pick a start time:</label>
            <input type="time" id="start-time" name="start-time" value="13:00">
            <span class="hidden error">Please enter a start time.</span>
            <span class="error <?= !isset($errors['start']) ? 'hidden' : ""; ?>">Please enter a start time </span>
          </div>

          <div>
            <label for="end-time">Pick a end time:</label>
            <input type="time" id="end-time" name="end-time" value="16:00">
            <span class="hidden error">Please enter an end time.</span>
            <span class="error <?= !isset($errors['end']) ? 'hidden' : ""; ?>">Please enter an end time </span>
          </div>

          <span id="time-error"class="hidden error">Start time cannot be after end time, or end time before start.</span>
          <span class="error <?= !isset($errors['invalid_time']) ? 'hidden' : ""; ?>">Start time cannot be after end time, or end time before start. </span>

          <div>
          <label for="duration">Select slot duration length:</label>
          <!-- values are stored in seconds, used to calculate time interval of each blank slot inserted on signup sheet creation-->
          <select name="duration" id="slotDuration">
              <option value="<?="300"?>">5 mins</option>
              <option value="<?="600"?>">10 mins</option>
              <option value="<?="900"?>">15 mins</option>
              <option value="<?="1800"?>">30 mins</option>
              <option value="<?="3600"?>">1 hour</option>
              <option value="<?="7200"?>">2 hours</option>
            </select>
          </div>

          <span id="duration-error"class="hidden error">Your start/end time is too short for the duration selected</span>

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
      <li><a href="index.php">Home</a></li>
        <li><a href="mailto:slot-it@gmail.com">Contact</a></li>
        <li><i class="fas fa-phone-square-alt"></i> : +1(705)-123-1234</li>
        <li> <img src="img/logo.png" alt="Slot-it logo"></li>
      </ul>
      <p>&copy; 2021 - Slot-It - All rights reserved</p>
      
    </footer>
    
</body>
</html>