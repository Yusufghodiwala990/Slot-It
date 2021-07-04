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
    $noOfSlots=$_POST['noOfSlots'];
    $startTime=$_POST['start-time'];
    $endTime=$_POST['end-time'];
    
    if($_POST['name']==null)
    {
      $errors['titleError']=true;
    }
    
    if($_POST['description']==null)
    {
      $errors['descriptionError']=true;
    }

    if($_POST['noOfSlots']==null)
    {
      $errors['slotError']=true;
    }
   //implement date time error later
    if($_POST['name']!=null && $_POST['description']!=null && $_POST['noOfSlots']!=null){
    $pdo = connectDB();
   
    $query = "INSERT INTO Signup_sheets (Title,Description,Owner_ID,Date_created,No_of_slots,No_of_signups,Start,End) values (?,?,?,NOW(),?,0,?,?)"; 
    $stmt = $pdo->prepare($query);
    $stmt->execute([$title,$description,$userid,$noOfSlots,$startTime,$endTime]);

    $Sheet_ID = $pdo->lastInsertId(); //https://www.php.net/manual/en/pdo.lastinsertid.php

    for($i=0;$i<$noOfSlots;$i++)
    {
      $query = "INSERT INTO Slots(Scheduled_slots,Sheet_ID) values (NOW(),?)"; 
      $stmt = $pdo->prepare($query);
      $stmt->execute([$Sheet_ID]);
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
    <link rel="stylesheet" href="styles/signup.css"/>
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

          <fieldset>
            <legend>Sheet Tracking</legend>

            <div>
              <input id="dAndT" name="tracking" type="radio" value="A" />
              <label for="dAndT">Track date and time</label>
            </div>

            <div>
              <input id="dOnly" name="tracking" type="radio" value="B" />
              <label for="dOnly">Track date</label>
            </div>
            
            <div>
              <input id="tOnly" name="tracking" type="radio" value="C" />
              <label for="tOnly">Track time</label>
            </div>

            <div>
              <input id="nOnly" name="tracking" type="radio" value="B" />
              <label for="nOnly">Track by name only; do not track date and time</label>
            </div>

          </fieldset>

          <fieldset>
            <legend>Fields</legend>
            <div>
              
              <input id="colEmail" name="collect" type="checkbox"/>
              <label for="colEmail">Collect email when the sheet is filled out</label>
              <br>
              <input id="colPhone" name="collect" type="checkbox"/>
              <label for="colPhone">Collect phone when the sheet is filled out</label>
            
            </div>
          </fieldset>
          
          <div>
            <label for="usrName">Your Name:</label>
            <input id="usrName" name="usrName" type="text" placeholder="Enter your name here">
          </div>

          <div>
            <label for="usrEmail">Your Email:</label>
            <input id="usrEmail" name="usrEmail" type="text" placeholder="Enter your email here">
          </div>

          <div>
          <label for="start-time">Pick a start time:</label>
          <input type="datetime-local" id="start-time" name="start-time" value="2021-06-14T20:50">
          </div>

          <div>
          <label for="end-time">Pick an end time:</label>
          <input type="datetime-local" id="end-time" name="end-time" value="2021-06-14T20:50">
          </div>

          <div>
            <label for="usrTimezone">Your Timezone</label>
            <select name="userTimezone" id="usrTimezone">
              <option value="">Choose One</option>
              <option value="1">Central Time [US & Canada]</option>
              <option value="2">Eastern Time</option>
              <option value="3">Western Time?</option>
              <option value="4">placeholder</option>
              <option value="5">pull timezones from an sql db, christ</option>
            </select>
          </div>

          <div>
            <label for="noOfSlots">Number of slots</label>
            <input id="noOfSlots" name="noOfSlots" type="number">
            <br>
            <span class="<?=!isset($errors['slotError']) ? 'hidden' : "error";?>">Please set the number of slots.</span>

          </div>

          <div>
            <button id="submit" name="submit">Next</button>
          </div>

        </form>

        </section>
      </main>
</body>