<?php
  include "library.php";
if(isset($_POST['submit'])){

/* for KRIS */


/* NOTE : Since we storing the time of the slots separately, we will need a TIME type column in mySQL 
(under date/time) */
$startDate = $_POST['start-date'];   // just a normal date (YYYY-MM-DD)  
$startTime = strtotime($_POST['start-time']); // get the start time in seconds for unix conversion later
$endTime = strtotime($_POST['end-time']);   // get the end time in seconds for unix conversion
$duration = $_POST['duration'];           // duration is just a string

echo $startDate;
echo "<br>";
echo $startTime;
echo "<br>";
echo $endTime;
echo "<br>";
echo $duration;
echo "<br>";


$intervals = array(); // an array to store all the time values

// loop to start from the startTime and run until the endTime (end time is > in seconds)
// then time forwards by the duration specified and inserted into the array in the format mySQL likes
for($time = $startTime; $time<=$endTime; $time+=$duration){
    $intervals[] = date('H:i:s',$time);
}

var_dump($intervals);

$pdo = connectDB();

// example insert

// $query = "INSERT INTO `intervals` VALUES(?)";
// $stmt = $pdo->prepare($query);
// $stmt->execute([$intervals[4]]);


$displayTime = "SELECT * FROM `intervals`";
$stmt = $pdo->prepare($displayTime);
 $stmt->execute();
 $rows = $stmt->fetchAll();
 

 // displaying the time slots (not a good way to return the same number of arrays as there are rows
//   but will do)
 foreach($rows as $row){
     echo $row['slot_interval'];
     echo "<br>";
 }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form id="createSheet" action="<?=htmlentities($_SERVER['PHP_SELF']);?>" method="post" novalidate>
<input type="date" id="start-date" name="start-date">
START<input type="time" name="start-time" value="13:00">
END <input type="time" name="end-time" value="16:00">
DURATION <select name="duration" id="usrTimezone">
              <option value="<?="300"?>">5 mins</option>
              <option value="<?="600"?>">10 mins</option>
              <option value="<?="900"?>">15 mins</option>
              <option value="<?="1800"?>">30 mins</option>
              <option value="<?="3600"?>">1 hour</option>
              <option value="<?="7200"?>">2 hours</option>
            </select>
<button id="submit" name="submit">SUBMIT</button>
</body>
</html>