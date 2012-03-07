<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>A Generic Page</title>
  <meta name="description" content="something good should go here">
  <meta name="author" content="your name should go here">

<?php
if($_GET['gender'] == "male" || $_GET['email'] == "george") {
  echo "<link rel=\"stylesheet\" href=\"stylesheets/male.css\">";
    echo "<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>";
} else {
  echo "<link rel=\"stylesheet\" href=\"stylesheets/female.css\">";
  echo "<link href='http://fonts.googleapis.com/css?family=Allura' rel='stylesheet' type='text/css'>";
}
?>



  <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->

  <!-- Base Stylsheets http://twitter.github.com/bootstrap/ -->
  <link rel="stylesheet" href="stylesheets/lib/bootstrap.css" >
  <link rel="stylesheet" href="stylesheets/lib/jquery.fancybox.css" >


  <!-- Your Stylesheets -->
<!--  <link rel="stylesheet" href="stylesheets/main.css" > -->
  <link href='http://fonts.googleapis.com/css?family=Luckiest+Guy' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="stylesheet.css" >


  <!--  jQuery http://jquery.com/ -->
  <script src="javascripts/lib/jquery-1.7.1.min.js"></script>

  <!-- Bootstrap Javascripts http://twitter.github.com/bootstrap/javascript.html -->
  <script src="javascripts/lib/bootstrap.js"></script>

  <!-- Fancybox Lightbox http://fancyapps.com/fancybox/#examples -->
  <script src="javascripts/lib/jquery.fancybox.js"></script>


  <!-- Your Javascripts -->
  <script src="javascripts/main.js"></script>

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js" type="text/javascript"></script>

  <script src="animalCanvas.js"></script>


<style type="text/css">

canvas {
  border: 1px solid black;
   position: relative;
}
</style>


  </head>

  <body onLoad="init();">
  <img src="images/logo.png">

  <div class="container">
  <div class="row">
  <div class="span12">
  <div class="main">
  <div class="row">
  <div class="span12">
  <div id="title">
 
  <h1>Momo's Room</h1>
    
  </div> <!-- end title -->
    
  <div class="row">
  <div class="span10">
  <div id="interact">
  <p><canvas width="750" height="450" id="canvas"></canvas></p>
  </div> <!-- end interact -->
    
  </div> <!-- end span8 -->
    
  <div class="span2">
  <div id="petbutton">
  <button type="submit" id="createbutton" class="btn btn-large btn-info" onClick="food.createFood();">Feed your pet</button>
  <p><br><button type="submit" id="createbutton" class="btn btn-large btn-success" onClick="animal.groom();">Groom your pet</button></p>
  <br><button type="submit" id="createbutton" class="btn btn-large btn-warning" onClick="ball.click();">Play with your pet</button>
  <p><br><button type="submit" id="createbutton" class="btn btn-large btn-danger" onClick="animal.clean();">Clean your pet</button></p>
  <br><p><button type="submit" id="createbutton" class="btn btn-large btn-primary" onClick="animal.sleep();">Sleep your pet</button></p>
  </div> <!-- end petbutton -->
  </div> <!-- end span2 -->
  </div> <!-- end row -->
  <br>
  </div> <!-- end main -->
  </div> <!-- end span12 -->
  </div> <!-- end row -->

  </div> <!-- end container -->    

  </body>
</html>