<?php

  include_once "db_connection.php";
  session_start();
  $page = "account";

  $database = new Connection();
  $conn = $database->openConnection();

  $message = "";
  $message_type = "";
  # grab all the users so we can populate the drop-downs on the page.
  /*
  $statement = $conn->prepare("SELECT * FROM morganfk7676;");
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
      $message = "Unable to add new Pokemon to database: " . $statement->errorCode() . ".";
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
    $exists = false;

    foreach ($user_tuples as $user) {
      //set session variables
      if($_POST['username'] === $user['username']){
        $_SESSION['user_id'] = $user['user_id'];
        if($user['hired'] === '1'){
          $_SESSION['hired'] = true;
        }
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
      unset($_SESSION['hired']);
      unset($_SESSION['user_id']);
      $message = "Logged out successfully!";
      $message_type = "success";
    } else {
      $message_type = "danger";
    }
  }

  ?>
*/

?>


<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="hunt.css">


    <title>Hunt Hunt | Account</title>
  </head>
  <body>
<!--color scheme hex codes:brown #663300	darkyellow #CC9900	dark green #333300	orange #CC6600 -->
<!-- p5.js libraries? -->
    <?php include 'nav.php' ?>
    <div class="container-fluid">

      <?php if($message !== ""){ ?>
				<div class="alert alert-<?php echo $message_type; ?>">
					<?php echo $message; ?>
				</div>
			<?php } ?>

			<div class="row card mx-auto">
				<div class="col-12">
					<div style="background:transparent !important" class="jumbotron">
						<h1 class="display-4 yellow">log in!</h1>
						<p class="lead text-center">Join us. Work. Join us. Work. Join us. Work. Join us. Work.</p>
					</div>
					<form method="post">
						<?php if(!isset($_SESSION['username'])){ ?>
							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" class="form-control" id="username" name="username" placeholder="Enter username">
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control" id="password" name="password" placeholder="Enter password">
							</div>
							<div class="form-group pt-3 text-center">
									<button type="submit" name="login" class="btn btn-dark">Login</button>
									<button type="submit" name="register" class="btn btn-dark">Register</button>
							</div>
						<?php }?>

						<div class="form-group pt-3 text-center">
							<?php if(isset($_SESSION['username'])){ ?>
								<button type="submit" name="logout" class="btn btn-dark">Logout</button>
							<?php } ?>
						</div>
					</form>
				</div>
				<div class="col-12 col-lg-6 mb-3">
					<img class="img-fluid rounded-more" alt="Hand of man in suit giving thumbs up" src="images/thumbsup.jpg"/>
				</div>
			</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
