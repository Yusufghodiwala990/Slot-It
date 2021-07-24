<?php
session_start();
if(isset($_SESSION['user_id'])){
 $filename = "profile-pic" . $_SESSION['user_id'];
 $profpicpath = "/home/yusufghodiwala/public_html/www_data/3420project_images/";

  $result = glob ($profpicpath . $filename . ".*" );
  
  if(empty($result))
    $picExists = false;
  else{
    $picExists = true;
    $profpic_url = "https://loki.trentu.ca/~yusufghodiwala/www_data/3420project_images/";
    $url = explode("/",$result[sizeof($result) - 1]);
    $profpic_url = $profpic_url . $url[sizeof($url)-1]; 
  }
  





} 

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slot-It</title>
    <link rel="stylesheet" href="styles/master.css"/>
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
   
  </head>
  <body>

    <header>
      <nav>
        <ul>
          <div>
            <img src="./img/logo.png" alt="Slot-it logo" width="60px" height="60px">
          </div>
          <div>
          
          <a href=""><li>Home</li></a>
          <a href="./scripts/search.php"><li>Search</li></a>
          
          <?php if(isset($_SESSION['user_id'])):?>
            <a href="create.php"><li>Create</li></a>
            <a href="./scripts/mystuff.php"><li>View</li></a>
            <a href="./scripts/edit_account.php"><li>My Account</li></a>
            <a href="./scripts/edit_account.php"><li>Logout</li></a>
            
               <?php if($picExists):?>
              <img src="<?=$profpic_url?>">

            <?php else:?>
            <i class="fa fa-user" aria-hidden="true"></i>
            <?php endif?>

            
            
          <?php endif ?>

          <?php if(!isset($_SESSION['user_id'])): ?>
            <a href="./scripts/registration.php"><li>Sign-up<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
            <a href="./scripts/login.php"><li>Login<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
          <?php endif ?>

        </div>
        </ul>
      </nav>      
    </header>

    <main>
      <section>
        <h2>Welcome to Slot-It!</h2>
        <div>
        <p>Instantly organize your schedule to spark <span>joy.</span></p>
        </div>
        <div>
          <p> Don't have an account?</p>
         <a href="./scripts/registration.php"><button>Sign-up</button></a>
          
        </div>
      
      </section>

      <section>
        

        <div>
          <h1>What is Slot-It?</h1>
          <p>Slot-It is a simple way to create,manage and publish online sign-up sheets. You can create sign-up sheets for people to sign up for, enter times and dates for a set of tasks and activites, and publish/share it.</p>
          <a href="./scripts/registration.php"><button>Try Now</button></a>
        </div>

        <div>
          <h1>Who is slot-it for?</h1>
          <p>Slot-It is free to use anyone, and everyone. Slot-It can be used for event sign-ups, classes, anything. If you need a sign-up sheet, especially one that's customizable, Slot-it is your friend. Slot-it is free, and always will be. </p>
          <a href="./scripts/search.php"><button>View a sample sign-up sheet</button></a>
        </div>

        <div>
          <h1>Is it really free?</h1>
          <p>Yes! Thanks to our remarkable team of unpaid interns, we managed to cut costs down by a whopping 100%! Our outsourced customer service team is always available to help sort out any issues you may have, so don't worry!</p>
          <a href="mailto:slot-it@gmail.com"><button>Contact Us</button></a>
        </div>

        
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
</html>