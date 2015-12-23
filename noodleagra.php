<?php

  session_start();

  $ch = curl_init();
  $query = $_SESSION['query'];
  $apiKey = 'AIzaSyAvfXfpPDiZW_5Q5FjiA6ig7QZVpt3lJ6M';
//Check if user has entered any queries
  if($query) {

    //Convert language to English
    //URL encode the query
    $url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.rawurlencode($query).'&target=en';
  
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                 
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

    $query_en = $responseDecoded['data']['translations'][0]['translatedText'];
    $encoded_en = rawurlencode($query_en); 

    $url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.rawurlencode($query).'&target=de';
  
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                 
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

    $query_de = $responseDecoded['data']['translations'][0]['translatedText'];
    $encoded_de = rawurlencode($query_de); 

    $url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.rawurlencode($query).'&target=ru';
  
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($handle);                 
    $responseDecoded = json_decode($response, true);
    curl_close($handle);

    $query_ru = $responseDecoded['data']['translations'][0]['translatedText'];
    $encoded_ru = rawurlencode($query_ru); 

    //Connect to the SOLR Server
    $finalQuery = "akshay164.koding.io:8983/solr/project/select?q=text_en%3A(".$encoded_en.")+OR+text_ru%3A(".$encoded_ru.")+OR+text_de%3A(".$encoded_de.")&rows=1000&fl=text_en%2Ctext_ru%2Ctext_de%2Ctweet_urls%2Ctweet_hashtags&wt=json&indent=true";

    curl_setopt($ch, CURLOPT_URL, $finalQuery);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);

    //Execute the query
    $re = curl_exec($ch);
    curl_close($ch);

    //Get result in readable JSON format
    $json = json_decode($re, true);

    //Check if json file is retrieved with some values
    if(! $json['response']['docs']) {
      //header('Location: solr.php?result=novalues');
      $_SESSION['result'] = "novalues";
      $_SESSION['finalquery'] = $finalQuery;
      header('Location: solragra.php');
      exit();
    }
  }
  else {
    //header('Location: solr.php?result=noinput');
    $_SESSION['result'] = "noinput";
    header('Location: solragra.php');
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
    
    <style type="text/css">

      
    </style>
  </head>

    <body>
      <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <a href="http://79.170.40.40/agradeepk.com/solragra.php" class="navbar-brand">Noodle Search</a>
            
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            
            </button>
          </div>

          <form class="navbar-form navbar-left">
            <div class="row">
              <a class="btn btn-success" href="http://79.170.40.40/agradeepk.com/solragra.php">Search Again!</a>        
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
    <!--<div class="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3 emailForm">
          <h1 align="center">AAPOUT Search Engine</h1>
          
          <p class="lead">Please provide your query!</p>
          <?php echo $result; ?>
          <form method="post">
            <div class="form-group">
              <label for="query">Your Query:</label>
              <input type="text" name="query" class="form-control" placeholder="Enter Your Query"/>
            </div>
            
            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Submit Query"/>
          </form>
        </div>
      </div>
    </div>-->
    
    <div class="container"> 
      <!--<textarea class="form-control" placeholder="Your output.." rows="50"><?php /*print_r($json);*/ ?></textarea>-->
      <?php
        $counter = 0;
        echo '<p class="alert alert-success searchDisplayer">Search results for '.$query.'</p>';
        //echo '<p class="alert alert-success searchDisplayer">Query used: '.$finalQuery.'</p>';
        echo '<ul class="list-group ul_class">';

        foreach($json['response']['docs'] as $item) {
        
          if(($counter%3)==0) {
            echo '<div class="row outputRow">';
          }
          
          $counter++;
          echo '<div class="col-md-4 emailForm">';
          echo '<li class="list-group-item liQueryOutput"><p class="headingTag">Tweet</p>';
          
          if($item['text_de']){
            echo '<p class="tweetText">'.$item['text_de'].'</p>';
          }

            if($item['text_en']){
            echo '<p class="tweetText">'.$item['text_en'].'</p>';
          }

          if($item['text_ru']){
            echo '<p class="tweetText">'.$item['text_ru'].'</p>';
          }

          if($item['tweet_urls']){
            echo "<br><p class='miniHeadersURLs'>URLs in Tweet</p>";
              foreach($item['tweet_urls'] as $values) {
                if($values){
                  echo '<p><a href="'.$values.'" target="_blank" class="urlTags">'.$values.'</a></p>';
                }
              }
          }

          if($item['tweet_hashtags']){
            echo "<br><p class='miniHeadersHT'>Hashtags in Tweet</p>";
            echo '<ul class="list-group ul_class">';
              foreach($item['tweet_hashtags'] as $values) {
                if($values){
                  echo '<li class="hashTags">#'.$values.'</li>';
                }
              }
          }
            echo '<div class="break"></div>';
            echo '<p class="tweetNumber">'.$counter.'</p>';
            echo '</li>';
            echo '</div>';
            if(($counter%3)==0) {
              echo '</div>';
            }
        }

        echo '</ul>';
      ?>      
    </div>

    <script type="text/javascript">

      if($(".tweetNumber").html() >= 1000) {
        $(".tweetNumber").css('text-indent','-41px');
        $(".tweetNumber").css('font-size','1.3em');
      }

      else if($(".tweetNumber").html() >= 100) {
        $(".tweetNumber").css('text-indent','-38px');
      }

    </script>

  </body>
</html>


