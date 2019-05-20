<?php

  include_once "db_connection.php";
  session_start();
  $page = "account";

  $database = new Connection();
  $conn = $database->openConnection();
  # grab all the users so we can populate the drop-downs on the page.

  $statement = $conn->prepare("SELECT * FROM users;");
  $statement->execute();
  $user_tuples = $statement->fetchAll();

  $message = "";
  $message_type = "";

  function updateName(){//have an option under accounts tab/user settings
    if(isset($_POST['name'])){
      $name = $_POST['name'];
      $updateStatement = $conn->prepare("UPDATE users SET name='$name' WHERE username='$username'");
      $updateStatement->execute();
    }
  }

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
    } else {
      $message_type = "danger";
    }
  }
  

?>

<!-- make account a modal/dropdown that you can login, or create new account,
        if logged in, can see your successful hunts + stats for that user on a seperate page
        update full name option, -->
          <!-- <div class="form-group">
            <label for="name">Full name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="First Last">
          </div> -->
