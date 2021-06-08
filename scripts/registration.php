<?php

$FREE_MARKS = "https://youtu.be/dQw4w9WgXcQ";
$sum = 2;
$daud = "here";
$kris = "here";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration</title>
  <link rel="stylesheet" href="../styles/registration.css" />
</head>

<body>
  <main>
  <section id="createaccount">
    <h1>Create Account</h1>
    <form method="POST" novalidate autocomplete="false">
    <aside>
  <i class="far fa-user-circle"></i>
  <input type="file" id="profilepic" name="profilepic">
  <label id="profilepic" for="profilepic">Choose Profile Picture(optional)</label>
    </aside>
    <div>
    <input type="text" name="name" placeholder="John Smith" id="name">
    <label for="name">Full Name</label>
    </div>
    <div>
    <input type="text" name="username" id="username" placeholder="derekpope666" autocomplete="off">
    <label for="username">Username</label>
    </div>
    <div>
    <input type="password" name="password" id="password" placeholder="inbaepn" autocomplete="off">
    <label for="password">Password</label>
    </div>
    <div>
    <a href=""><button>Back</button></a>
    <a href=""><button>Sign-up</button></a>
    </div>
    </form>
  </section>
  </main>
  <footer>
  <img src= "logo.png" alt='The website logo'/>
      <p>&copy; 2021 - Slot-It</p>
  </footer>
      
</body>
</html>