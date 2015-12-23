<?php

  session_start();

  $ch = curl_init();
  $query = $_SESSION['query'];
  $apiKey = 'AIzaSyAvfXfpPDiZW_5Q5FjiA6ig7QZVpt3lJ6M';

//Check if user has entered any queries
  if($query and ! isset($_GET['facethashtagValue'])) {

    $_GET['facethashtagValue'] = NULL;
    $_SESSION['updatedQuery'] = NULL;
    $_SESSION['facetArray'] = NULL;
    $_SESSION['facetQuery'] = NULL;
    $_SESSION['neFacetArray'] = NULL;
    $_SESSION['dAJson'] = NULL;

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
    $finalQuery = "agra1992.koding.io:8983/solr/newcore/select?q=text_en%3A(".$encoded_en.")+OR+text_ru%3A(".$encoded_ru.")+OR+text_de%3A(".$encoded_de.")&rows=100&fl=text_en%2Ctext_ru%2Ctext_de%2Ctweet_urls%2Ctweet_hashtags&wt=json&indent=true&facet=true&facet.field=tweet_hashtags";

    //$finalQuery = "akshay164.koding.io:8983/solr/project/select?q=text_en%3A(".$encoded_en.")+OR+text_ru%3A(".$encoded_ru.")+OR+text_de%3A(".$encoded_de.")&rows=1000&fl=text_en%2Ctext_ru%2Ctext_de%2Ctweet_urls%2Ctweet_hashtags&wt=json&indent=true&facet=true&facet.field=tweet_hashtags";

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
      header('Location: solr.php');
      exit();
    }
  }
  elseif(isset($_GET['facethashtagValue'])) {
    //Convert language to English
    //URL encode the query
    //echo "COUNT: ".count($_SESSION['facetArray'])."<br>";

    if(count($_SESSION['facetArray']) == 0) {
      $_SESSION['facetArray'] = array();
      $_SESSION['neFacetArray'] = array();
    }

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

    //print_r("Get Variable: ".$_SESSION['facethashtagValue']."<br>");

    $encodedHashTag = rawurlencode($_GET['facethashtagValue']);
    
    //echo "Encoded Get Variable: ".$encodedHashTag."<br>";

    $i = -1;
    $blnInput = false;

    if($_SESSION['facetArray']) {
      foreach($_SESSION['facetArray'] as $value) {
        $i++;

        $url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.$value.'&target=en';
  
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);                 
        $responseDecoded = json_decode($response, true);
        curl_close($handle);

        $value_en = $responseDecoded['data']['translations'][0]['translatedText'];

        $url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.$encodedHashTag.'&target=en';
  
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($handle);                 
        $responseDecoded = json_decode($response, true);
        curl_close($handle);

        $encodedHashTag_en = $responseDecoded['data']['translations'][0]['translatedText'];

        //echo "English Get Variable: ".$encodedHashTag_en."<br>";
        //echo "English Current Variable: ".$value_en."<br>";

        if(strtolower($value_en) == strtolower($encodedHashTag_en)) {

          unset($_SESSION['facetArray'][$i]);
          unset($_SESSION['neFacetArray'][$i]);

          $_SESSION['facetArray'] = array_values($_SESSION['facetArray']);
          $_SESSION['neFacetArray'] = array_values($_SESSION['neFacetArray']);

          //print_r($_SESSION['neFacetArray']);

          $blnInput = false;
          break;
        }
        else {
          $blnInput = true;
        }
      }

      if($blnInput) {
        array_push($_SESSION['facetArray'],  $encodedHashTag);
        array_push($_SESSION['neFacetArray'],  $_GET['facethashtagValue']);
        //print_r($_SESSION['neFacetArray']);
      }
    }
    else {
      array_push($_SESSION['facetArray'],  $encodedHashTag);
      array_push($_SESSION['neFacetArray'],  $_GET['facethashtagValue']);
      //print_r($_SESSION['neFacetArray']);
    }

    $facetQuery = "";
    foreach ($_SESSION['facetArray'] as $value) {
      if($value) {
        $facetQuery = $facetQuery.'&fq=tweet_hashtags:'.$value;
      }
    }

    //Connect to the SOLR Server
    /*if(isset($_SESSION['updatedQuery'])){
      foreach ($_SESSION['facetArray'] as $value) {
        $finalQuery = $_SESSION['updatedQuery'].'&fq=tweet_hashtags:'.$encodedHashTag;
      }
      
      $_SESSION['updatedQuery'] = $finalQuery;
    }
    else {
      $finalQuery = "akshay164.koding.io:8983/solr/project/select?q=text_en%3A(".$encoded_en.")+OR+text_ru%3A(".$encoded_ru.")+OR+text_de%3A(".$encoded_de.")&rows=1000&fl=text_en%2Ctext_ru%2Ctext_de%2Ctweet_urls%2Ctweet_hashtags&wt=json&indent=true&facet=true&facet.field=tweet_hashtags&fq=tweet_hashtags:".$encodedHashTag;
      $_SESSION['updatedQuery'] = $finalQuery;
    }
    */
    $finalQuery = "agra1992.koding.io:8983/solr/newcore/select?q=text_en%3A(".$encoded_en.")+OR+text_ru%3A(".$encoded_ru.")+OR+text_de%3A(".$encoded_de.")&rows=100&fl=text_en%2Ctext_ru%2Ctext_de%2Ctweet_urls%2Ctweet_hashtags&wt=json&indent=true&facet=true&facet.field=tweet_hashtags".$facetQuery;

    //$finalQuery = "akshay164.koding.io:8983/solr/project/select?q=text_en%3A(".$encoded_en.")+OR+text_ru%3A(".$encoded_ru.")+OR+text_de%3A(".$encoded_de.")&rows=1000&fl=text_en%2Ctext_ru%2Ctext_de%2Ctweet_urls%2Ctweet_hashtags&wt=json&indent=true&facet=true&facet.field=tweet_hashtags".$facetQuery;

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
      header('Location: solr.php');
      exit();
    }
  }
  else {
    //header('Location: solr.php?result=noinput');
    $_SESSION['result'] = "noinput";
    header('Location: solr.php');
    exit();
  }

  /*
  function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
  }
  */
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <script src="http://cdn.jsdelivr.net/jquery.cookie/1.4.0/jquery.cookie.min.js"></script>

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
            <a href="http://79.170.40.40/agradeepk.com/solr.php" class="navbar-brand" id="newSearcher1">Noodle Search</a>
            
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            
            </button>
          </div>

          <form class="navbar-form navbar-left">
            <div class="row">
              <a class="btn btn-success" id="newSearcher2" name="newSearch" href="http://79.170.40.40/agradeepk.com/solr.php">Search Again!</a>        
            </div>
          </form>

          <form class="navbar-form navbar-left">
            <div class="row">
              <a class="btn btn-success analytic_button" name="dataAnalytics" id="dataAnalytics" href="http://79.170.40.40/agradeepk.com/datanalytics.php">Data Analytics</a>
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
            <p>
            Group Name: AAPOUT535</br>
              Twinkle Asthana         50169071</br>
              Uttara Asthana          50168804</br>
              Oshin Patwa             50169203</br>
              Akshay Kumar            50169103</br>
              Prithvi G Indrakumar    50169089</br>
              Agradeep Khanra         50169071</br>
            </p>
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

        require_once 'alchemyapi.php';
        $alchemyapi = new AlchemyAPI();

        $i = 0;
        $counter = 0;
        $itemName = "";

        $current = array();
        $inner = array();
        $outer = $inner;

        echo '<p class="alert alert-success searchDisplayer">Search results for "'.$query.'". Total search items retrieved: '.$json['response']['numFound'].'</p>';
        //echo '<p class="alert alert-success searchDisplayer">'.$finalQuery.'</p>';
        //print_r($_SESSION['facetArray']);

        echo'<div class="major_div">';
        echo'<div class="facet_div">';

        $intC = 0;
        echo '<ul class="list-group ul_class facet_ul">';

        if($json['facet_counts']['facet_fields']['tweet_hashtags']) {
          echo '<a href="#li_hide" data-toggle="collapse" class="facet_link"><p class="facet_head">Search by choosing Hashtags<span class="glyphicon glyphicon-chevron-down facet_arrow"></span></p></a>';
          echo'<div id="li_hide" class="collapse">';

          foreach($json['facet_counts']['facet_fields']['tweet_hashtags'] as $item) {
            $current[] =  $item;

            if(($intC%2) == 0) {
              $actualString = $item;
              $itemName = $item;
              //echo '<script type="text/javascript"> alert('.$actualString.') </script>';

              //echo '<li class="list-group-item">';
              //echo'<label><input type="checkbox" name="'.$item.'">'.$item;
            }
            else {
              if($item > 0) {
                
                $count = count($_SESSION['neFacetArray']);
                $counter = 0;

                //echo $count;

                /*if(isset($_SESSION['neFacetArray'])) {
                  foreach ($_SESSION['neFacetArray'] as $value) {

                    //convert $item and $value to English for Proper string comparisons
                    $url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.rawurlencode($item).'&target=en';
  
                    $handle = curl_init($url);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($handle);                 
                    $responseDecoded = json_decode($response, true);
                    curl_close($handle);

                    $item_en = $responseDecoded['data']['translations'][0]['translatedText'];

                    echo $item_en;

                    $url = 'https://www.googleapis.com/language/translate/v2?key='.$apiKey.'&q='.rawurlencode($value).'&target=en';

                    $handle = curl_init($url);
                    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                    $response = curl_exec($handle);                 
                    $responseDecoded = json_decode($response, true);
                    curl_close($handle);

                    $value_en = $responseDecoded['data']['translations'][0]['translatedText'];

                    echo $value_en;

                    if(strtolower($item_en) == strtolower($value_en)) {
                      echo "Inside3";
                      $counter++;

                      echo '<li class="list-group-item facet_li" id="facetList">';
                      echo'<label class="facetLabel facet_label"><input type="checkbox" id="'.$intC.'" onclick="myFunction(this)" name="'.$itemName.'" class="facetCheckBox facet_check" />'.$actualString.'</label>';
                     // echo '<p> ('.$item.')</p>';
                      echo '</li>';
                    }
                  }
                }
                */
                
                  $actualString = $actualString.' ('.$item.')';
                  //echo '<script type="text/javascript"> alert('.$actualString.') </script>';
                  echo '<li class="list-group-item facet_li" id="facetList">';
                  /*echo'<label class="facetLabel facet_label"><input type="checkbox" id="'.$intC.'" onclick="myFunction(this)" name="'.$itemName.'" class="facetCheckBox facet_check" /><?php if($_isset[$_SESSION["'.$itemName.'"]) { "checked"="checked"; } ?>'.$actualString.'</label>';*/
                  echo'<label class="facetLabel facet_label"><input type="checkbox" id="'.$itemName.'" onclick="myFunction(this)" name="'.$itemName.'" class="facetCheckBox facet_check" /><?php if($_isset[$_SESSION["'.$itemName.'"]) { "checked"="checked"; } ?>'.$actualString.'</label>';

                 // echo '<p> ('.$item.')</p>';
                  echo '</li>';
                
              }
              $actualString = "";
            }
            $intC++;
          }
         echo'</div>';
        }

        echo '</ul>';
        echo '</div>';

        $intCounter = 0;

        for($i=0; ; $i++)
        {
          if($intCounter == 10) {
            break;
          }

          $intCounter++;
          $inner=array();
          $inner['name'] = rawurldecode($current[$i]);
          $inner['y'] = $current[($i+1)];
          $outer[] = $inner;
          $i++;          
        }

        $jsonDataAnalytics=json_encode($outer);
        //echo "data analytics";
        //print_r($jsonDataAnalytics);

        $_SESSION['dAJson'] = $jsonDataAnalytics;

        echo'<div class="data_div">';
        echo '<ul class="list-group ul_class">';


        foreach($json['response']['docs'] as $item) {
        
          if(($counter%2)==0) {
            echo '<div class="row outputRow">';
          }
          
          $counter++;
          echo '<div class="col-md-6 emailForm">';
          echo '<li class="list-group-item liQueryOutput"><p class="headingTag">Social Media Posts</p>';
          
          if($item['text_de']){
            $demo_text = $item['text_de'];
            echo '<p class="tweetText">'.$item['text_de'].'</p>';
          }

          if($item['text_en']){
            $demo_text = $item['text_en'];
            echo '<p class="tweetText">'.$item['text_en'].'</p>';
          }

          if($item['text_ru']){
            $demo_text = $item['text_ru'];
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
            echo '<ul class="list-group ul_class" id="ul_class">';
              foreach($item['tweet_hashtags'] as $values) {
                if($values){
                  echo '<li class="hashTags">#'.$values.'</li>';
                }
              }
          }

          $response = $alchemyapi->entities('text',$demo_text, array('sentiment'=>1));

          if ($response['status'] == 'OK') {
            echo "<br><p class='contentHeader'>Content Tagging Information</p>";
            echo '<button class="btn btn-success dropdown-toggle contentButton" type="button" data-toggle="modal" data-target="#contentModal'.$counter.'">View</button>';
            //echo '## Response Object ##', "<br>";
            //echo print_r($response);

            //echo "<br>";
            //echo '## Entities ##', "<br>";
            echo '<div class="modal fade" id="contentModal'.$counter.'">
              <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  <h4 class="modal-title">Content Tagging Information</h4>
                </div>

                <div class="modal-body">';
                  foreach ($response['entities'] as $entity) {
                    echo '<p class= "contentTagInfo">';
                    echo 'entity: ', $entity['text'], "<br>";
                    echo 'type: ', $entity['type'], "<br>";
                    echo 'relevance: ', $entity['relevance'], "<br>";
                    echo 'sentiment: ', $entity['sentiment']['type'];       
                    if (array_key_exists('score', $entity['sentiment'])) {
                      echo ' (' . $entity['sentiment']['score'] . ')', "<br>";
                    }else {
                      echo "<br>";
                    }

                    echo "</p>";
                    echo "<br>";
                  }
                echo '</div>
                <div class="modal-footer">
                  <a href="#" data-dismiss="modal" class="btn">Close</a>
                </div>
                </div>
              </div>
            </div>';
          }
          else {
            echo "No content tagging information available";
          }

            echo '<div class="break"></div>';
            echo '<p class="tweetNumber">'.$counter.'</p>';
            echo '</li>';
            echo '</div>';
            if(($counter%2)==0) {
              echo '</div>';
            }
        }

        echo '</ul>';
        echo'</div>';
        echo'</div>';
      ?>      
    </div>

    <script type="text/javascript">

      $.cookie.json = true;
      repopulateFormELements();

      $(document).ready(function(){

        $('#li_hide').on('shown.bs.collapse', function () {
           $(".glyphicon-chevron-down").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up");
        });

        $('#li_hide').on('hidden.bs.collapse', function () {
           $(".glyphicon-chevron-up").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down");
        });

      })

      /*if($(".tweetNumber").html() >= 1000) {
        $(".tweetNumber").css('text-indent','-41px');
        $(".tweetNumber").css('font-size','1.3em');
      }

      else if($(".tweetNumber").html() >= 100) {
        $(".tweetNumber").css('text-indent','-38px');
      }

      var el = document.getElementById('facetList');
      var tops = el.getElementsByTagName('input');
      console.debug(tops);

      var len=tops.length;
      alert("Length:" + len);

      for (var i=0; i<len; i++) {
          if ( tops[i].type === 'checkbox' ) {
              tops[i].onclick = function() {
                  alert("Hi!");
              }
          }
      }*/

      function repopulateFormELements(){
        var elementValuesNew = $.cookie('elementValuesNew');
        if(elementValuesNew){
          Object.keys(elementValuesNew).forEach(function(element) {
            var checked = elementValuesNew[element];
            $("#" + element).prop('checked', checked);
          });
          $("button").text(elementValuesNew["buttonText"])
        }
      }

      function updateCookie(){
        var elementValuesNew = {};

        $(":checkbox").each(function(){
          elementValuesNew[this.id] = this.checked;
        });
        $.cookie('elementValuesNew', elementValuesNew, { expires: 7, path: '/' })
      }

      $(":checkbox").on("change", function(){
        updateCookie();
      });

/*
      function repopulateCheckboxes(){
        
        var checkboxValues = $.cookie('checkboxValues');

        if(checkboxValues){
          alert(element);
          Object.keys(checkboxValues).forEach(function(element) {
            var checked = checkboxValues[element];

            $("#" + element).prop('checked', checked);
          });
        }
      }
*/
      /*$(function(){
          var value = localStorage.input === 'true' ? true: false; // 1
          $('input').prop('checked', value || false);
      });
      */

      /*$(".facet_check").on("change", function(){
        alert("hi!");
        var checkboxValues = {};
        $(":facet_check").each(function(){
          checkboxValues[this.id] = this.checked;
        });
        $.cookie('checkboxValues', checkboxValues, { expires: 7, path: '/' })
        console.debug($.cookie('checkboxValues'));
      });
      */

      /*$(".facet_check").checked(function(){
        alert("hi!");
        var checkboxValues = {};
        $(":facet_check").each(function(){
          checkboxValues[this.id] = this.checked;
        });
        $.cookie('checkboxValues', checkboxValues, { expires: 7, path: '/' })
        console.debug($.cookie('checkboxValues'));
      });
      */

      /*function myFunction(cb) {
        //var nodeList = document.getElementsByTagName("INPUT");
        //alert(cb.name);
        var checkboxValues = {};
        checkboxValues[cb.id] = cb.checked;
        window.location.href = "noodle.php?facethashtagValue=" + cb.name;
        $.cookie('checkboxValues', checkboxValues, { expires: 7, path: '/' });
      }*/

      /*$('input').on('change', function() {
          localStorage.input = $(this).is(':checked');
          console.log($(this).is(':checked'));
      });*/
      
      

      function myFunction(cb) {
        //var nodeList = document.getElementsByTagName("INPUT");
        //alert(cb.name);
        window.location.href = "noodle.php?facethashtagValue=" + cb.name;
      }      

      /*
      $(":checkbox").on("change", function(){
        $.cookie.json = true;

        var checkboxValues = {};
        $checkboxValues = this.checked;

        $.cookie('checkboxValues', $checkboxValues, { expires: 7, path: '/' })
      }

      function repopulateCheckboxes(){
        var checkboxValues = $.cookie('checkboxValues');
        if(checkboxValues){
          Object.keys(checkboxValues).forEach(function(element) {
            var checked = checkboxValues[element];
            $("#" + element).prop('checked', checked);
          });
        }
      }
      */

      $("#newSearcher2").click(function() {
        $.cookie('elementValuesNew', 'noodle.php', { expires: -1, path: '/' });
        //$.removeCookie('elementValuesNew');
      });

      $("#newSearcher1").click(function() {
        $.cookie('elementValuesNew', 'noodle.php', { expires: -1, path: '/' });
      });

    </script>

  </body>
</html>


