<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Sheet</title>
    <link rel="stylesheet" href="../styles/edit_sheet.css"/>
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
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
          <a href="../create.html"><li>Create</li></a>
          <a href="./mystuff.php"><li>View</li></a>
          <a href="./login.php"><li>Login <i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
          <a href="./edit_account.php"><li>My Account <i class="fa fa-user" aria-hidden="true"></i></li></a>
        </div>
        </ul>
      </nav>      
    </header>

    <main>
    <section>
        <h2>Edit Sheet</h2>
        <form method="POST" novalidate autocomplete="false" enctype="multipart/form-data">
        <div>
          <input type="text" name="name" placeholder="John Smith" id="name">
          <label for="name">Sheet Name</label>
        </div>

        <div>
            <label for="description" id="special">Description</label>
            <textarea name="description"  id="description" cols="84" rows="5"></textarea>
          </div>


          <div>
          <input type="date" name="extend" id="extend" placeholder="inbaepn" autocomplete="off">
          <label for="extend">Extend Date</label>
        </div>

        <div>
          <input type="number" name="slots" id="slots" placeholder="inbaepn" autocomplete="off">
          <label for="slots">Add Slots</label>
        </div>

        <div>
          <a href=""><button type="button">Remove Slots</button></a>    
        </div>

        <div>

          <a href=""><button>Back</button></a>
        </div>


      </form>
    </section>

    <div class="deletebutton">
      <a href=""><button>Delete Sheet</button></a>
      </div>
    
    </main>
</body>
</html>