<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Slot-It</title>
    <link rel="stylesheet" href="styles/viewslot.css"/>
    <script src="https://kit.fontawesome.com/6ab0b12156.js" crossorigin="anonymous"></script>
  </head>

<body>

    <header>
        <nav>
          <ul>
            <div>
            <a href=""><li>Home</li></a>
            <a href="create.html"><li>Create</li></a>
            
            <a href="./scripts/mystuff.php"><li>View</li></a>
            <a href="./scripts/login.php"><li>Login<i class="fa fa-sign-in" aria-hidden="true"></i></li></a>
            <a href="./scripts/edit_account.php"><li>My Account<i class="fa fa-user" aria-hidden="true"></i></li></a>
          </div>
          </ul>
        </nav>      
      </header>

      <main>
        <section>
          <h1>Viewing Slot for Sheet:</h1>
          <table>
            <thead>
              <tr>
                <th scope="col">Purpose</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">Name</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Dentist appointment</td>
                <td>10th July 2021</td>
                <td>10:00am</td>
                <td>Daud</td>
              </tr>
            </tbody>
          </table>

          <div>
            <a href="scripts/viewing.php">Back</button>
            <a href="cancelslot.html">Cancel Slot</button>
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