<?php
  session_start();

  echo "Data Analytics JSON:<br>";
	if(isset($_SESSION['dAJson'])){
    echo "yo!";
  }
  else {
    echo "No!";
  }

  print_r($_SESSION['dAJson']);
?>

<!doctype html>
<html>
	<head>
		<title>Noodle Search Engine</title>

		<meta charset="utf-8" />
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

              <!-- jQuery UI -->
		<link href="css/smoothness/jquery-ui-1.9.0.custom.css" rel="stylesheet">
		<script language="javascript" type="text/javascript" src="jquery-1.8.2.js"></script>
		<script src="js/jquery-ui-1.9.0.custom.js"></script>
               <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
               <!--Highcharts-->
               <script src="https://code.highcharts.com/highcharts.js"></script>
               
		
		
	     <!-- Bootstrap Core CSS -->
           <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
         <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
         <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		
		
		
		<link href="css/style.css" rel="stylesheet">

	</head>

	<body>

  
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <a href="http://79.170.40.40/agradeepk.com/solr.php" class="navbar-brand">Noodle Search</a>
            
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            
            </button>
          </div>

          <form class="navbar-form navbar-left">
            <div class="row">
              <a class="btn btn-success" name="newSearch" href="http://79.170.40.40/agradeepk.com/solr.php">Search Again!</a>        
            </div>
          </form>

          <form class="navbar-form navbar-left">
            <div class="row">
              <a class="btn btn-success analytic_button" name="newSearch" href="http://79.170.40.40/agradeepk.com/noodle.php">Back to Search Results</a>        
            </div>
          </form>

          <div class="navbar-collapse">
            
            <form class="navbar-form navbar-right">
              <button class="btn btn-success dropdown-toggle" type="button" data-toggle="modal" data-target="#myModal">About Us</button>
            </form>
              
          </div>

        </div>
      </div>
      <!-- Modal for About Us -->
      <div class="modal fade" id="myModal">
        <div class="modal-dialog">
          <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title">About Us</h4>
          </div>
          <div class="modal-body">
            <p>Enter Some text here..</p>
          </div>
          <div class="modal-footer">
            <a href="#" data-dismiss="modal" class="btn">Close</a>
          </div>
          </div>
        </div>
      </div>
         <div class="container">
          <div id="container_chart" style="min-width: 600px; max-width:600px; height: 400px; margin: 0 auto"></div>
        </div>
          <script type="text/javascript">

          var dAJson = <?php echo $_SESSION['dAJson']; ?>;

         $(function () { 
    var chart = new Highcharts.Chart({
                chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie',
           renderTo:'container_chart'
        },
        title: {
            text: 'Sample Heading'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [{
                name: 'Microsoft Internet Explorer',
                y: 56.33
            }, {
                name: 'Chrome',
                y: 24.03
            }, {
                name: 'Firefox',
                y: 10.38
            }, {
                name: 'Safari',
                y: 4.77
            }, {
                name: 'Opera',
                y: 0.91
            }, {
                name: 'Proprietary or Undetectable',
                y: 0.2
            }]
        }]
    });
});
          </script>
	</body>
</html>
