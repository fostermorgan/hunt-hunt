<?php

  include_once "db_connection.php";
  session_start();
  $page = "account";
  $database = new Connection();
  $conn = $database->openConnection();
  global $message;
  global $message_type;

?>

<!-- make account a modal/dropdown that you can login, or create new account,
        if logged in, can see your successful hunts + stats for that user on a seperate page
        update full name option, -->
          <!-- <div class="form-group">
            <label for="name">Full name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="First Last">
          </div> -->


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

    <div class="container">
      <?php if($message !== ""){ ?>
        <div id="jsErrorMessage" class="alert alert-<?php echo $message_type; ?>" role="alert"><?php echo $message; ?></div>
      <?php } ?>

			<div class="row card mx-auto">

				<div class="col-12">
					<div style="background:transparent !important" class="jumbotron">
						<h1 class="display-4"><?php echo $_SESSION['username']; ?>'s Profile!</h1>
						<p class="lead text-center">View stats and change name here</p>
					</div>
				</div>
        <div class="col-12">
          <?php
            $user_id = $_SESSION['user_id'];
            $updateStatement = $conn->prepare("SELECT * FROM hunts WHERE user_id='$user_id';");
            $updateStatement->execute();
            $userHunts = $updateStatement->fetchAll();
            $nHunts = 0;
            $nSuccess = 0;
            foreach($userHunts as $hunt){
              $nHunts += 1;
              if($hunt['isSuccess'] == 'true'){
                $nSuccess += 1;
              }
            }
          ?>
          <h4>Total number of hunts: <?php echo $nHunts; ?></h4>
          <h4>Number of successful hunts: <?php echo $nSuccess; ?></h4>
          <?php if($nHunts != 0){ ?><h4>Success Rate: <?php  echo $nSuccess/$nHunts *100 . '%'; ?></h4> <?php }?>
          <h4>Favorite Location: </h4>

        </div>
			</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
