<?php

  include_once "db_connection.php";
  session_start();
  $page = 'report';

  $database = new Connection();
  $conn = $database->openConnection();

  # grab all the users so we can populate the drop-downs on the page.
  // $statement = $conn->prepare("SELECT * FROM morganfk7676;");
  // $statement->execute();
  // $user_tuples = $statement->fetchAll();

  $message = "";


?>


<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="hunt.css">


    <title>Hunt Hunt | Report</title>
  </head>
  <body>
<!--color scheme hex codes:brown #663300	darkyellow #CC9900	dark green #333300	orange #CC6600 -->
<!-- p5.js libraries? -->
    <?php include 'nav.php' ?>
    <div class="container-fluid">

      <div class="card row center green text-light mx-auto">
        <!-- <div class="col-12 col-lg-12 yellow"> -->
        <div class="col-12">

					<div class="jumbotron text-dark">
						<h1 class="display-4">Landed a successful hunt?</h1>
						<p class="lead">Submit your hunt here!</p>
					</div>

					<form method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label for="name">Full name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Foo Barrington">
						</div>
						<div class="form-group">
							<label for="title">Desired title</label>
							<input type="text" class="form-control" id="title" name="title" placeholder="CEO">
						</div>
						<div class="form-group">
							<label for="photo">Recent photo (300x470, jpg format)</label>
							<input type="file" class="form-control-file" id="photo" name="photo">
						</div>
						<div class="form-group pt-3 text-center">
							<button type="submit" class="btn btn-dark" name="apply">Submit!</button>
						</div>
					</form>
				</div>
        <div class="col-12 col-lg-6 mb-3">
					<img class="img-fluid rounded-more" alt="Man in suit pointing at camera" src="images/you.jpg"/>
				</div>

      </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
