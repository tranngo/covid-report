<?php

	require "config/config.php";

	// DB Connection
	$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	if ( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
		exit();
	}

	$mysqli->set_charset('utf8');

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

		<div class="modal" tabindex="-1" role="dialog">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title" style="color: black">Confirm</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <p style="color: black">Are you sure you want to delete this?</p>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-primary confirm-delete">Delete</button>
		        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		  </div>
		</div>

		<div class="container" id="resources">
			<div class="row">
				<div class="col-12 text-right">
					<a class="btn btn-secondary" data-toggle="collapse" href="#viewOrganizations" role="button" aria-expanded="false" aria-controls="collapseExample">
					    View Organizations
					</a>
				  <div class="btn-group" role="group">
				    <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				      Add
				    </button>
				    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
				      <a class="dropdown-item" href="add_resource.php">Resource</a>
				      <a class="dropdown-item" href="add_organization.php">Organization</a>
				    </div>
				  </div>
				</div>
			</div>
			<div class="row my-3">
				<div class="col-12">
					<div class="collapse" id="viewOrganizations">
					  <div class="card card-body" id="organizationsList">
					  	<?php
				  			$sql = "SELECT * FROM organizations;";

							$results = $mysqli->query($sql);

							if ( $results == false ) {
								echo $mysqli->error;
								exit();
							}
					  	?>

					  	<?php if ($results->num_rows == 0) : ?>
					  		<h5>No available organizations.</h5>
					  	<?php endif; ?>

					  	<ul>
						  	<?php while ( $row = $results->fetch_assoc() ) : ?>
								<li><?php echo $row['organization']; ?> (<a href="<?php echo $row['link']; ?>" target="_blank">Link</a>)</li>
							<?php endwhile; ?>
						</ul>
					  </div>
					</div>
				</div>
			</div>
			<div class="row my-3">
				<div class="col-12">
					<h3>Health</h3>
				</div>
				<div class="col-12 overflow-auto rounded" id="health">
					<div class="row">
						<?php
							$sql = "SELECT resources.id, resources.description, resources.link, organizations.organization
									FROM resources
										JOIN organizations
											ON resources.organization_id = organizations.id
									WHERE category_id = 1;";

							$results = $mysqli->query($sql);

							if ( $results == false ) {
								echo $mysqli->error;
								exit();
							}
						?>
						<?php while ( $row = $results->fetch_assoc() ) : ?>
							<div class="col-12 col-md-6 col-lg-4">
								<div class="card mb-3">
									<div class="card-body text-center">
										<h5><?php echo $row['organization']; ?></h5>
										<p class="card-text overflow-auto"><?php echo $row['description']; ?></p>
										<div class="text-center">
											<a href="<?php echo $row['link']; ?>" target="_blank">Link</a> <a class="ml-5 text-warning" href="edit_resource.php?resource_id=<?php echo $row['id']; ?>">Edit</a> <a id="<?php echo $row['id']; ?>" class="ml-5 text-danger delete">Delete</a>
										</div>
									</div>
								</div>
							</div>

						<?php endwhile; ?>

					</div>
				</div>
			</div>

			<div class="row my-3">
				<div class="col-12">
					<h3>Financial</h3>
				</div>
				<div class="col-12 overflow-auto rounded" id="financial">
					<div class="row">

						<?php
							$sql = "SELECT resources.id, resources.description, resources.link, organizations.organization
									FROM resources
										JOIN organizations
											ON resources.organization_id = organizations.id
									WHERE category_id = 2;";

							$results = $mysqli->query($sql);

							if ( $results == false ) {
								echo $mysqli->error;
								exit();
							}

						?>

						<?php while ( $row = $results->fetch_assoc() ) : ?>
							<div class="col-12 col-md-6 col-lg-4">
								<div class="card mb-3">
									<div class="card-body text-center">
										<h5><?php echo $row['organization']; ?></h5>
										<p class="card-text overflow-auto"><?php echo $row['description']; ?></p>
										<div class="text-center">
											<a href="<?php echo $row['link']; ?>" target="_blank">Link</a> <a class="ml-5 text-warning" href="edit_resource.php?resource_id=<?php echo $row['id']; ?>">Edit</a> <a id="<?php echo $row['id']; ?>" class="ml-5 text-danger delete">Delete</a>
										</div>
									</div>
								</div>
							</div>

						<?php endwhile; ?>
					</div>

				</div>
			</div>

			<div class="row my-3">
				<div class="col-12">
					<h3>Community</h3>
				</div>
				<div class="col-12 overflow-auto rounded" id="community">
					<div class="row">
						<?php
							$sql = "SELECT resources.id, resources.description, resources.link, organizations.organization
									FROM resources
										JOIN organizations
											ON resources.organization_id = organizations.id
									WHERE category_id = 3;";

							$results = $mysqli->query($sql);

							if ( $results == false ) {
								echo $mysqli->error;
								exit();
							}

						?>

						<?php while ( $row = $results->fetch_assoc() ) : ?>
							<div class="col-12 col-md-6 col-lg-4">
								<div class="card mb-3">
									<div class="card-body text-center">
										<h5><?php echo $row['organization']; ?></h5>
										<p class="card-text overflow-auto"><?php echo $row['description']; ?></p>
										<div class="text-center">
											<a href="<?php echo $row['link']; ?>" target="_blank">Link</a> <a class="ml-5 text-warning" href="edit_resource.php?resource_id=<?php echo $row['id']; ?>">Edit</a> <a id="<?php echo $row['id']; ?>" class="ml-5 text-danger delete">Delete</a>
										</div>
									</div>
								</div>
							</div>

						<?php endwhile; ?>
					</div>
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

		<?php
			// Close DB Connection.
			$mysqli->close();
		?>

		<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha256-R4pqcOYV8lt7snxMQO/HSbVCFRPMdrhAFMH+vr9giYI=" crossorigin="anonymous"></script>
    	<script src="js/resources.js"></script>
	</body>
</html>