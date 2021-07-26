<?php 
session_start();
/* FORMAT OF THE PROFILE PICTURE STORED ON LOKI : profile-pic{ID}.extension stored
   in 3420project_images folder in www_data on yusufghodiwala account  */
if(isset($_SESSION['user_id'])){

   // fetching the profile picture if available.

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
   $url = explode("/",$result[sizeof($result) - 1]);  // get the latest pic the user uploaded
   $profpic_url = $profpic_url . $url[sizeof($url)-1]; // build URL
 }


} include "library.php";


$pdo = connectDB();
$errors = array();

// get the sheet id from the url
$Sheet_ID = $_GET['SheetID']??null;

// delete sheet, yes or no (modal)
if(isset($_POST['no'])){
  $Sheet_ID =$_POST['no'];
}

if(!isset($_SESSION['user_id']))
{
  header("location:login.php");   //redirect to login if session doesn't have user_id
  exit;
}


// fetch the signup sheet details
$query1 = "select * from Signup_sheets where ID=?"; 
$stmt1 = $pdo->prepare($query1);
$stmt1->execute([$Sheet_ID]);
$result1 = $stmt1->fetch();


if (isset($_POST['submit'])) {
// pull variables to update table
  $Title = $_POST['name'] ?? null;
  $description = $_POST['description'] ?? null;
  $start = $_POST['start'] ?? null;
  $end = $_POST['end'] ?? null;
  $startTime = $_POST['start-time'];
  $endTime = $_POST['end-time'];   
  $duration = $_POST['duration'];
  $searchable = $_POST['searchability'] ?? false;


  // get the start and end time into a UNIX timestamp
  $startInSeconds = strtotime($startTime);
  $endInSeconds = strtotime($endTime);

$date_now = new DateTime();
$date_entered = new DateTime($start);
if($date_entered < $date_now){
  $errors['date'] = true;
}


 
  
  if($searchable==true){
    $searchable=1;
  }

  else{
    $searchable=0;
  }

// validation for title and desc
  if (!isset($Title) || strlen($Title) == 0) {
    $errors['title'] = true;
  }

  if (!isset($description) || strlen($description) == 0) {
    $errors['desc'] = true;
  }


  // checking if start and end time are valid
  if($startInSeconds > $endInSeconds || $endInSeconds < $startInSeconds ){
    $errors['invalid_time'] = true;
  }


  // checking if start or end time are empty
  if($startTime==''){
      $errors['start'] = true;
  }

  if($endTime==''){
    $errors['end'] = true;
  }

  


  


  //function to check if there's an overlap between the new set of time slots to be inserted and the old set
function checkRange ($min, $max, $value){
  if(filter_var(
    $value, 
    FILTER_VALIDATE_INT, 
    array(
        'options' => array( // options array which defines min max
            'min_range' => $min, 
            'max_range' => $max
        )
    )
)){
  return true;      // if it's range
} else{
  return false;    // not in range
 
}

}
  // only check for overlaps if new start and end time were entered to allow update
  // of other fields

  if($result1['StartTime'] !== $startTime && $result1['EndTime'] !== $endTime){

  //set to true if overlap exists between start_time and the previous(result1) starttime-endtime
  if(checkRange(strtotime($result1['StartTime']),strtotime($result1['EndTime']),$startInSeconds))
    $errors['overlap'] = true;

  // set to true if overlap exists between end time and the previous(result1) starttime-endtime
  if(checkRange(strtotime($result1['StartTime']),strtotime($result1['EndTime']),$endInSeconds))
    $errors['overlap'] = true;

  
  // check if overlaps the entire previous starttime and endtime
  if(checkRange($startInSeconds,$endInSeconds,strtotime($result1['EndTime'])))
    $errors['overlap'] = true;

}
  // update if no errors
    if(count($errors) == 0){
      $query2 = "update Signup_sheets set Title= ?, Description=?, StartDate=?, StartTime=?, EndTime=?, SlotDuration=?, searchable=? where ID=?"; 
      $stmt2 = $pdo->prepare($query2);
      $stmt2->execute([$Title,$description,$start,$startTime,$endTime,$duration,$searchable,$Sheet_ID]);

      
        $intervals = array(); // an array to store all the time values
       
       


        // loop from start-time to endtime the user provided and forward the loop
        // by the duration in seconds the user provided
        for($time = $startInSeconds; $time<=$endInSeconds; $time+=$duration){
          $intervals[] = date('H:i:s',$time); // populate the interval array with each slot 
                                              // timestamp
        }
        

        // insert each interval into the slots table
        foreach($intervals as $sTime)
        {
          $query = "INSERT INTO Slots(StartTime,Sheet_ID) values (?,?)"; 
          $stmt = $pdo->prepare($query);
          $stmt->execute([$sTime,$Sheet_ID]);
        }

      header("Location:mystuff.php");
      exit;
      }
}

// for deleting a signup sheet
if(isset($_POST['yes'])){
$Sheet_ID = $_POST['yes'];
  $query2 = "DELETE from Slots where Sheet_ID = ?"; 
  $stmt2 = $pdo->prepare($query2);
  $stmt2->execute([$Sheet_ID]);

  $query3 = "DELETE from Signup_sheets where ID = ?";  
  $stmt3 = $pdo->prepare($query3);
  $stmt3->execute([$Sheet_ID]);

  header("location:mystuff.php");
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sheet</title>
    <link rel="stylesheet" href="../styles/edit_sheet.css"/>
    <link rel="stylesheet" href="../styles/errors.css" />
    <script defer src="deleteModal.js"></script>
    <script defer src="edit_sheet.js"></script>
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>

</head>
<body>
<header>
      <nav>
        <ul>
          <div>
            <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
          </div> <!-- Navbar-->
          <div>
          <a href="../index.php"><li>Home</li></a>
          <a href="./search.php"><li>Search</li></a>
          
          <?php if(isset($_SESSION['user_id'])):?>
            <a href="../create.php"><li>Create</li></a>
            <a href="./mystuff.php"><li>View</li></a>
            <a href="./edit_account.php"><li>My Account</li></a>
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>">
            
            
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
        <h2>Edit Sheet</h2>
        <form id="editForm" method="POST" novalidate autocomplete="false" enctype="multipart/form-data">
        <div>
          <input type="text" name="name" autocomplete="off"value="<?=$result1['Title']?>" id="name">
          <label for="name">Sheet Name</label>
          <span class="error <?= !isset($errors['title']) ? 'hidden' : ""; ?>">Please enter a title </span>

        </div>

        <div>
            <label for="description" id="special">Description</label>
            <textarea name="description"  id="description" cols="84" rows="5" autocomplete="off"><?=$result1['Description']?></textarea>
            <span class="hidden error">Please set a description.</span>
            <span class="error <?= !isset($errors['desc']) ? 'hidden' : ""; ?>">Please set a description </span>
        </div>


        <div>
          <input type="date" name="start" id="start" value="<?=$result1['StartDate']?>" autocomplete="off">
          <label for="start">Start Date</label>
          <span class="hidden error">Start date cannot be before today's date.</span>
          <span class="error <?= !isset($errors['date']) ? 'hidden' : ""; ?>">Start Date cannot be before today's date </span>
        </div>

        <div>
            <input type="time" id="start-time" name="start-time" value="<?=$result1['StartTime']?>">
            <label for="start-time">Pick a start time:</label>
            <span class="hidden error">Please enter a start time.</span>
            <span class="error <?= !isset($errors['start']) ? 'hidden' : ""; ?>">Please enter a start time </span>
        </div>

        <div>
            <input type="time" id="end-time" name="end-time" value="<?=$result1['EndTime']?>">
            <label for="end-time">Pick a end time:</label>
            <span class="hidden error">Please enter an end time.</span>
            <span class="error <?= !isset($errors['end']) ? 'hidden' : ""; ?>">Please enter an end time </span>
        </div>
        
        <span id="time-error"class="hidden error">Start time cannot be after end time, or end time before start.</span>
        <span class="error <?= !isset($errors['invalid_time']) ? 'hidden' : ""; ?>">Start time cannot be after end time, or end time before start. </span>
        
        <div>
          <label for="duration">Select slot duration length:</label>
          <select name="duration" id="slotDuration">
              <!-- values are stored in seconds, used to calculate time interval of each blank slot inserted on signup sheet update-->
              <option value="<?="300"?>" <?php if($result1['SlotDuration']==300) echo 'selected'?> >5 mins</option>
              <option value="<?="600"?>" <?php if($result1['SlotDuration']==600) echo 'selected'?>>10 mins</option>
              <option value="<?="900"?>" <?php if($result1['SlotDuration']==900) echo 'selected'?>>15 mins</option>
              <option value="<?="1800"?>" <?php if($result1['SlotDuration']==1800) echo 'selected'?>>30 mins</option>
              <option value="<?="3600"?>" <?php if($result1['SlotDuration']==3600) echo 'selected'?>>1 hour</option>
              <option value="<?="7200"?>" <?php if($result1['SlotDuration']==7200) echo 'selected'?>>2 hours</option>
            </select>
          </div>

          
          <span class="error <?= !isset($errors['overlap']) ? 'hidden' : ""; ?>">There is an overlap between this set and the current set of slots. Please check your start/end times </span>
        <div>
            <input id="searchable" name="searchability" type="checkbox" <?php if($result1['searchable']==1) echo 'checked';?> />
            <label for="searchable">Make my sheet searchable</label> 
        </div>

        <div>
          <a href=""><button type="submit" name="submit">Submit</button></a>
        </div>


      </form>
    </section>
    
    </main>
    <div class="deletebutton">
    <button id="delete">Delete Sheet</button>
  </div>
  <form action="<?= htmlentities($_SERVER['PHP_SELF']); ?>" method="post" novalidate autocomplete="false" enctype="multipart/form-data">

  <div id="ModalWindow" class="modal">

  <div class="content">
  <p>Are you sure you want to continue?</p>
          <div>
            <button type="submit" name="no" value=<?=$Sheet_ID?> id="no">No</button>
            <button type="submit" name="yes" value=<?=$Sheet_ID?> id="yes">Yes</button>
          </div>  
  </div>

</div>
</form>

</body>
</html>