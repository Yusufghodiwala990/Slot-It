<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="../styles/edit_account.css"/>
</head>
<body>
<header>
      <nav>
        <ul>
          <div>
            <img src="../img/logo.png" alt="Slot-it logo" width="60px" height="60px">
          </div>
          <div>
          <a href="../index.html"><li>Home</li></a>
          <a href=""><li>Create</li></a>
          
          <a href="trentu.ca"><li>View</li></a>
          
          <a href="login.php"><li>My Account<i class="fa fa-user" aria-hidden="true"></i></li></a>
        </div>
        </ul>
      </nav>      
    </header>

    <main>
    <section>
        <h2>Account Information</h2>
        <form method="POST" novalidate autocomplete="false" enctype="multipart/form-data">
        <aside>
          <input class="profpic" type="file" id="profilepic" name="profilepic">
          <label class="profpic" id="profilepic" for="profilepic">Change Profile Picture<i class="far fa-user-circle"></i></label>
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
     
        <div>
        <a href="../index.html"><button type="button">Back</button></a>
        <a href="registration.php"><button>Sign-Up</button></a>
        </div>
      </form>
    </section>

    <div class="deletebutton">
      <a href=""><button>Delete Account</button></a>
      </div>
    
    </main>
</body>
</html>