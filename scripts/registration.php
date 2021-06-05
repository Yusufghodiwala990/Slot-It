<?php

$FREE_MARKS = "https://youtu.be/dQw4w9WgXcQ";
$sum= 2;
$daud= "here";
$kris ="here";

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
  <section id="createaccount">
  <h1>Create Account</h2>
  <form action="register.php" method="post">
          <div id="firstname">
           <label for="firstname">First-Name:</label>
           <input               
              id="firstname"
              name="firstname"
              type="text"
              placeholder="Alan"
              />
          </div>
          <div id="lastname">
           <label for="lastname">Last-Name:</label>
           <input               
              id="lastname"
              name="lastname"
              type="text"
              placeholder="Smith"
              />
          </div>
          <div id="userid">
           <label for="username">User-Name:</label>
           <input               
              id="username"
              name="username"
              type="text"
              />
          </div>
          <div id="email">
           <label for="email">Email:</label>
           <input               
              id="email"
              name="email"
              type="email"
              placeholder="AlanSmith@gmail.com"
              />
          </div>
          <div id="pass">
           <label for="pass">Password:</label>
           <input               
              id="pass"
              name="password"
              type="password"
              />
          </div>
          <div id="confirmpass">
           <label for="pass">Confirm Password:</label>
           <input               
              id="pass"
              name="password"
              type="password"
              />
          </div>
          <button id="submit" name="submit">Sign Up</button>
  </form>
  </section>
  <section id="have_an_account">
    <p>Already have an account? <button id="submit" name="submit">Login</button></p>
    <img src= "logo.png" alt='The website logo'/>
  </section>
  </body>