<?php
	session_start();

	if(isset($_POST['submit'])) {
		$_SESSION['result'] = "";
		$_SESSION['query'] = $_POST['query'];
		header('Location: noodle.php');
		exit();
	}

	
?>

<!doctype html>
<html>
	<head>
		<title>Noodle Search Engine</title>

		<meta charset="utf-8" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		
		<!-- Bootstrap Core CSS -->
	    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		
		<!-- jQuery UI -->
		<link href="css/smoothness/jquery-ui-1.9.0.custom.css" rel="stylesheet">
		<script language="javascript" type="text/javascript" src="jquery-1.8.2.js"></script>
		<script src="js/jquery-ui-1.9.0.custom.js"></script>
		
		<link href="css/style.css" rel="stylesheet">

	</head>

	<body>
		<div class="container">
			<div class="row">
				<div class="logo_div">
				  <img src="images/logo-IR.png" class="logo_image"></img>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
				
					<?php
						if(isset($_SESSION['result'])) {
							if(strcmp($_SESSION['result'], "novalues") == 0) {
								//echo $_SESSION['finalquery'];
								echo "<p class='alert alert-danger'>No results found. Please input other search terms.</p>";
							}
							elseif(strcmp($_SESSION['result'], "noinput") == 0) {
								//echo $_SESSION['finalquery'];
								echo "<p class='alert alert-danger'>Please input some search terms.</p>";
							}
						}
						
					?>
					
				</div>

				<div class="col-md-6 search_box">
					<form method="post">
						<div class="input-group">
						  <input type="text" name = "query" class="form-control height-change" placeholder="Search for...">
						  <span class="input-group-btn">
						    <button class="btn btn-default backcolor-blue height-change-2" name="submit" type="submit"><span class="glyphicon glyphicon-search white_color" aria-hidden="true"></span></button>
						  </span>
						</div><!-- /input-group -->
					</form>
				</div>
			</div><!-- /.row -->
		</div>
	</body>

	<script type="text/javascript">

	</script>
</html>
