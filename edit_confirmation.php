<?php
	require "config/config.php";
	$isUpdated = false;

	if ( !isset($_POST['organization']) || empty($_POST['organization'])
		|| !isset($_POST['link']) || empty($_POST['link'])
		|| !isset($_POST['category']) || empty($_POST['category'])) {

		// Missing required fields.
		$error = "Please fill out all required fields.";

	} else {

		// DB Connection
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ( $mysqli->connect_errno ) {
			echo $mysqli->connect_error;
			exit();
		}

		$mysqli->set_charset('utf8');

		if ( isset($_POST['description']) && !empty($_POST['description']) ) {
			$description = $_POST['description'];
		} else {
			$description = "";
		}

		$statement = $mysqli->prepare("UPDATE resources SET description = ?, link = ?, organization_id = ?, category_id = ? WHERE id = ?");
		$statement->bind_param('ssiii', $description, $_POST['link'], $_POST['organization'], $_POST['category'], $_POST['resource_id']);

		$executed = $statement->execute();
		if(!$executed) {
			echo $mysqli->error;
			exit();
		}

		// affected_rows will return how many records have been inserted/updated from the above statement
		if($statement->affected_rows == 1) {
			$isUpdated = true;
		}

		$statement->close();


		$mysqli->close();
	}

?>

<!doctype html>
<html lang="en">
	<head>
		<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	<title>COVID-19 Report</title>

    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    	<link rel="stylesheet" type="text/css" href="css/style.css">
    	<script src="https://kit.fontawesome.com/2b38a0d5c8.js" crossorigin="anonymous"></script>
    	<link href="https://fonts.googleapis.com/css2?family=Karla:wght@400;700&display=swap" rel="stylesheet">
	</head>
	<body>
		<div class="container" id="header">
			<div class="row">
				<div class="col-12">
					<nav class="navbar navbar-dark justify-content-center">
  						<a class="navbar-brand" href="index.html">
    						<h1><i class="fas fa-shield-virus fa-lg"></i> COVID-19 Report</h1>
  						</a>
					</nav>
				</div>
			</div>
		</div>

		<div class="container" id="nav">
			<div class="row">
				<div class="col-12">
					<ul class="nav justify-content-center">
						<li class="nav-item">
					    	<a class="nav-link" href="index.html">Home</a>
					  	</li>
					  	<li class="nav-item">
					    	<a class="nav-link active" href="resources.php">Resources</a>
					  	</li>
					  	<li class="nav-item">
					    	<a class="nav-link" href="bookmarks.html">Bookmarks</a>
					  	</li>
					</ul>
				</div>
			</div>
		</div>


		<div class="container mb-5">
			<div class="row">
				<div class="col-12">


					<?php if ( isset($error) && !empty($error) ) : ?>
						<div class="alert alert-danger" role="alert">
						  	<?php echo $error; ?>
						</div>
						<div class="form-group row text-center">
							<div class="col-sm-3"></div>
							<div class="col-sm-6 mt-2">
								<a href="edit_resource.php?resource_id=<?php echo $_POST['resource_id']; ?>">Back</a>
							</div>
							<div class="col-sm-3"></div>
						</div>
					<?php endif; ?>

					<?php if ($isUpdated) : ?>
						<div class="alert alert-success" role="alert">
						  	<span class="font-italic"><?php echo $_POST['link']; ?></span> has been edited successfully.
						</div>
						<div class="form-group row text-center">
							<div class="col-sm-3"></div>
							<div class="col-sm-6 mt-2">
								<a href="resources.php">Confirm</a>
							</div>
							<div class="col-sm-3"></div>
						</div>

					<?php endif; ?>
				</div>
			</div>
		</div>
		

		<div class="container" id="footer">
			<div class="row">
				<div class="col-12 mt-2 mb-5">
					<p class="card-text">Crafted with <i class="fas fa-heart" id="heart"></i> by <a href="https://www.tranngo.com/">Tran Ngo</a></p>
				</div>
			</div>
		</div>

		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
	</body>
</html>