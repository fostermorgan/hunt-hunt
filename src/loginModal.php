<?php

  include_once "db_connection.php";
  // session_start();
  $page = "loginModal";

  $database = new Connection();
  $conn = $database->openConnection();
  # grab all the users so we can populate the drop-downs on the page.

  $statement = $conn->prepare("SELECT * FROM users;");
  $statement->execute();
  $user_tuples = $statement->fetchAll();

  $message = "";
  $message_type = "";

  function addNewUser(){
    global $conn;
    global $message;
    $statement = $conn->prepare("INSERT INTO users (username,password) VALUES (:username,:password);");
    $statement->bindValue(":username",$_POST['username']);
    $statement->bindValue(":password",password_hash($_POST['password'],PASSWORD_DEFAULT));

    $insert_success = $statement->execute();
    if(!$insert_success){
      $message = "Unable to add user to database: " . $statement->errorCode() . ".";
      return false;
    }

    return true;
  }

  function verifyNewUser(){
    global $message;
    global $message_type;
    global $user_tuples;

    foreach ($user_tuples as $user) {
      if($user['username'] === $_POST['username']){//check inputed username with all database usernames to check if it exists
        $message = 'User already exists!';
        return false;
      }
    }
    return true;
  }

  function verifyUser(){
    global $message;
    global $message_type;
    global $user_tuples;
    global $conn;
    $exists = false;

    foreach ($user_tuples as $user) {
      //set session variables
      if($_POST['username'] === $user['username']){
        $_SESSION['user_id'] = $user['user_id'];
      }


      if($user['username'] === $_POST['username']){
        $exists = true;
        if(!password_verify($_POST['password'], $user['password'])){//make sure the passwords match
          $message = 'Password does not match!';
          return false;
        }
      }
    }



    if(!$exists){
      $message = 'No user ' . $_POST['username'] . ' exists.';
      return false;
    }
    return true;
  }

  function logout(){
    return session_destroy();
  }

  if(isset($_POST["register"])){//the user wants to add themself
    if(verifyNewUser() && addNewUser()){
      $message = "New user " . $_POST['username'] . " registered!";
      $message_type = "success";
    } else {
      $message_type = "danger";
    }
  }
  if(isset($_POST["login"])){//the user wants to login
    if(verifyUser()){
      $message = "User " . $_POST['username'] . " logged in!";
      $message_type = "success";
      $_SESSION['username'] = $_POST['username'];
      header("refresh: 2;");
      header("Location: account.php");
    } else {
      $message_type = "danger";
    }
  }
  if(isset($_POST["logout"])){//the user wants to logout
    if(logout()){
      unset($_SESSION['username']);
      unset($_SESSION['user_id']);
      unset($_SESSION['hasHuntedBefore']);
      $message = "Logged out successfully!";
      $message_type = "success";
      // header("refresh: 2;");
      //
      header("Location: index.php");

    } else {
      $message_type = "danger";
    }
  }
  if(isset($_POST["profile"])){
    header("Location: account.php");
  }

?>

<!-- make account a modal/dropdown that you can login, or create new account,
        if logged in, can see your successful hunts + stats for that user on a seperate page
        update full name option, -->
          <!-- <div class="form-group">
            <label for="name">Full name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="First Last">
          </div> -->

                  <?php if(isset($_SESSION['username'])){ ?>
                    <li id="logout" class="mx-auto mr-auto">
                      <div class="row">
                        <div class="col-md-12 mx-auto mr-auto">
                          <form method="post">
                            <div class="form-group">
                              <button type="submit" name="profile" class="btn btn-primary btn-block btn-dark">My Profile</button>
                              <button type="submit" name="logout" class="btn btn-primary btn-block btn-dark">Logout</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </li>
                  <?php } else {?>
                  <li id="login">
                    <div class="row pb-0 mb-0">
                      <div class="col-lg-12">
                        <form method="post">
                          <!-- // class='px-4 py-3'> -->
                          <div class="form-group">
            								<label for="username">Username</label>
            								<input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
            							</div>
                          <div class="form-group">
            								<label for="password">Password</label>
            								<input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
            							</div>
                          <span></span>
                          <div class="form-group">
                             <button type="submit" name="login" class="btn btn-block btn-primary btn-dark">Login</button>
                             <button type="submit" name="register" class="btn btn-primary btn-block btn-dark">Register</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </li>
                <?php } ?>
