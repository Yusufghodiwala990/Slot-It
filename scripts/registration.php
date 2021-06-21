

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Registration</title>
  <link rel="stylesheet" href="../styles/registration.css" />
  <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
</head>

<body>
  <main>
    <section id="createaccount">
      <h1>Create Account</h1>
      <form method="POST" novalidate autocomplete="false" enctype="multipart/form-data">
        <aside>
          <input class="profpic" type="file" id="profilepic" name="profilepic">
          <label class="profpic" id="profilepic" for="profilepic">Choose Profile Picture(optional)<i class="far fa-user-circle"></i></label>
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
          <input type="password" name="conpass" id="conpass" placeholder="inbaepn" autocomplete="off">
          <label for="conpass">Confirm Password</label>
        </div>
     
        <div id="links-reg">
          <a href="../index.html"><button type="button">Back</button></a>
          <a href=""><button>Sign-up</button></a>
        </div>
      </form>
    </section>
  </main>
  <footer>
    
    <p>&copy; 2021 - Slot-It</p>
  </footer>

</body>

</html>