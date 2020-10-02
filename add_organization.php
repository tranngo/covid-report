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

		<div class="container my-4">
			<div class="row">
				<div class="col-12 text-center">
					<h1>Add Organization</h1>
				</div>
				<div class="col-12">
					<form action="add_organization_confirmation.php" method="POST">
						<div class="form-group">
					    	<label for="organization-name">Name <span class="text-danger">(Required)</span></label>
					    	<input type="text" class="form-control" id="organization-name" name="organization-name" placeholder="Enter organization name">
					  	</div>

					  	<div class="form-group">
					    	<label for="organization-link">Link</label>
					    	<input type="text" class="form-control" id="organization-link" name="organization-link" placeholder="Enter organization website">
					  	</div>

						<div class="form-group row text-center">
							<div class="col-sm-3"></div>
							<div class="col-sm-6 mt-2">
								<button type="submit" class="btn btn-primary">Add</button>
								<button type="reset" class="btn btn-light">Reset</button>
							</div>
							<div class="col-sm-3"></div>
						</div>
						<div class="form-group row text-center">
							<div class="col-sm-3"></div>
							<div class="col-sm-6 mt-2">
								<a href="resources.php">Cancel</a>
							</div>
							<div class="col-sm-3"></div>
						</div>
					</form>
				</div>
			</div>
		</div>
		

		<div class="container" id="footer">
			<div class="row">
				<div class="col-12 mt-5">
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