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
<!--    <link rel="stylesheet" href="stylesheets/main.css" > -->
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

<script type="application/x-javascript">

var canvas;
var goToMouse = false;
var left= false;
var right = false;
var up = false;
var down = false;
var choice = 10;

function Vector (x, y) {
   this.x = x;
   this.y = y;
}

function Entity (location, image, width, height) {
   this.location = location;
   this.image = image;
   this.velocity = new Vector(0.0, 0.0);
   this.width = height;
   this.height = height;
   this.updateCall= function() {};
}
Entity.prototype.setVel = function(xv, yv) {
   this.velocity = new Vector(xv, yv);
};
Entity.prototype.setVel = function(vel) {
   this.velocity = vel;
};
Entity.prototype.update = function() {
   if(this.location.y<this.height/2 ||
      this.location.y>(canvas.height-this.height/2)) {
       this.velocity.y = -this.velocity.y;
       if (this.location.y < this.height/2) this.location.y = this.height/2 + 1;
       if (this.location.y > canvas.height - this.height/2) this.location.y = canvas.height - this.height/2 - 1.0;
   }
    if(this.location.x<this.width/2 ||
      this.location.x>(canvas.width-this.width/2)) {
       this.velocity.x = -this.velocity.x
       if (this.location.x < this.width/2) this.location.x = this.width/2 + 1;
       if (this.location.x > canvas.width - this.width/2) this.location.x = canvas.width - this.width/2 - 1.0;
   }
   this.location = new Vector(this.location.x + this.velocity.x,
                              this.location.y + this.velocity.y)
   
   this.updateCall();
};
Entity.prototype.draw = function(context) {
   context.save();
   context.translate(this.location.x, this.location.y);
   context.drawImage(this.image, -this.width/2, -this.height/2,
                     this.width, this.height);
   context.restore();
};

var sleep = 0;
var chase = 1;
var roam = 2;
var eat = 3;
var stand = 4;

// Handler methods: --------
// Food: food.createFood();
// Ball: ball.click();
// Groom: animal.groom();
// Clean: animal.clean();

// Entity objects
var animal;
var ball;
var food;

var ballImg = new Image();
var foodImg = new Image();


function init() {
//window.addEventListener('keydown',doKeyDown,true);

   canvas = document.getElementById("canvas");

   imgObj = new Image();
   imgObj.src='http://askalexia.com/wp-content/uploads/2010/08/apple_ipad_bumper-case-150x150.jpg';
   
   foodImg.src = 'http://www.baxterboo.com/images/products/large/paw-lickers-natural-dog-treats-peanut-butter-cookies-1.jpg';
   ballImg.src = 'http://www.benutextiles.com/full-images/tennis-ball-felt-758304.jpg';

   // Create animal ------------------------------------------
   animal = new Entity(new Vector(100.0, 100.0), imgObj, 100.0, 100.0);
    animal.state = roam;
    animal.timer = 1000;
    animal.hunger = 100.0;
    animal.dirty = 0.0;
    animal.shaggy = 0.0;
    animal.energy = 100.0;
    animal.happiness = 100.0;
    // Animal update proxy
    animal.updateCall = function() {
       animal.timer -= 1;
       if (animal.timer < 0) {
          animal.timer = 0;
       }
       if (animal.state == sleep && (animal.timer == 0 || animal.energy == 100)) {
          animal.state = roam;
          animal.roam();
       }
       
       // Handle full/hunger
       animal.full -= -0.01;
       if (animal.full <= 0) {
           animal.image = deathImage;
           animal.setVel(new Vector(0.0, 0.0));
       }
       if (animal.full < 10) {
           if (food.visible) {
               animal.state = eat;
           }
       }
       
       // Handle dirtiness
       animal.dirty += 0.015;
       if (animal.dirty > 100.0) animal.dirty = 100.0;
       if (animal.dirty < 0.0) animal.dirty = 0.0;
       
       // Handle shagginess
       animal.shaggy += -0.2;
       if (animal.shaggy > 100.0) animal.shaggy = 100.0;
       if (animal.shaggy < 0.0) animal.shaggy = 0.0;
       
       // Handle energy
       if (animal.state == chase) {
           animal.energy -= 0.05;
       } else if (animal.state == roam) {
           animal.energy -= 0.02;
       } else if (animal.state == sleep) {
           animal.energy += 0.1;
       }
       if (animal.energy < 0.0) animal.energy = 0.0;
       if (animal.energy > 100.0) animal.energy = 100.0;
       
       
       // Handle happiness
       if (animal.state == chase) {
          animal.happiness += 0.1;
       } else if (animal.state == roam) {
          animal.happiness -= 0.01;
       } else if (animal.state == sleep) {
          animal.happiness -= 0.1;
       } else {
          animal.happiness -= 0.1;
       }
       if (animal.happiness < 0.0) {
          animal.happiness = 0.0;
          if (animal.timer == 0) animal.state = chase;
       }
       if (animal.happiness > 100.0) animal.happiness = 100.0;
   }
   // Animal roam func
   animal.roam = function() {
       animal.setVel(new Vector(Math.random() * 2.0 - 1.0, Math.random() * 2.0 - 1.0));
   }
   // Animal eating handler
   animal.eating = function() {
       full = full - 1.0;
       if (full < 0.0) full = 0.0;
   };
   // Animal groom handler
   animal.groom = function() {
       animal.shaggy -= 10.0;
       if (animal.shaggy < 0.0) animal.shaggy = 0.0;
   };
   // Animal clean handler
   animal.clean = function() {
       animal.dirty -= 10.0;
       if (animal.dirty < 0.0) animal.dirty = 0.0;
   };

   animal.play = function() {
    animal.happiness += 5.0;
    if(animal.happiness > 100) animal.happiness = 100;
   };

   animal.sleep = function() {
    animal.state=sleep;
    animal.timer=5000;
   }

   
   // Create ball ------------------------------------------
   ball = new Entity(new Vector(200.0, 200.0), ballImg, 30.0, 30.0);
   ball.setVel(new Vector(2.0, 2.0));
   ball.updateCall = function() {
       ballXDir = ball.velocity.x / Math.abs(ball.velocity.x);
       ballYDir = ball.velocity.y / Math.abs(ball.velocity.y);
       newXVel = (ball.velocity.x != 0) ? ball.velocity.x - ballXDir/200.0 : 0.0;
       newYVel = (ball.velocity.y != 0) ? ball.velocity.y - ballYDir/200.0 : 0.0;
       
       ball.setVel(new Vector((Math.abs(newXVel) < .02) ? 0.0 : newXVel, (Math.abs(newYVel) < .02) ? 0.0 : newYVel));
       
   }
   // Ball click handler
   ball.click = function() {
      ball.setVel(new Vector(Math.random() * 8 - 4, Math.random() * 8 - 4));
      animal.play();
   };
   
   // Create food ------------------------------------------
   food = new Entity(new Vector(400.0, 50.0), foodImg, 50.0, 50.0);
   food.visible = false;
   // Food creation handler
   food.createFood = function() {
   
   };


   animal.setVel(new Vector(1, 1));
   
    ctx = canvas.getContext("2d");
    x = canvas.width/2;
    y = canvas.height/2;
   a = 0.0;



   setInterval("update()", 1000/100);
   setInterval("draw()", 1000/60);
   //setInterval("ball.click()", 3000);
}

function randomize() {
    dx+= 2*((Math.random()-.5)-(dx/20));
    dy+= 2*((Math.random()-.5)-(dy/20));
}

function update() {
   if (ball.velocity.x != 0 || ball.velocity.y != 0 && animal.timer == 0) {
      animal.state = chase;
   }
   if (animal.energy < 10.0){
      animal.state = sleep;
      animal.timer = 10000;
   }
      
   if (animal.state == chase) {
      xDelta = -animal.location.x + ball.location.x;
      yDelta = -animal.location.y + ball.location.y;
      
      if (Math.abs(xDelta) < animal.width/2 && Math.abs(yDelta) < animal.height/2) {
        animal.state = roam;
        animal.roam();
        animal.timer = 3000;
      } else {
        animal.setVel(new Vector(xDelta / Math.abs(xDelta), 
                               yDelta / Math.abs(yDelta)));
      }
   } else if (animal.state == sleep) {
      animal.setVel(new Vector(0.0, 0.0));
   }
   animal.update();
   ball.update();
}

function draw() {
    ctx.restore();
   clearCanvas();
   ctx.save();

   ctx.fillText("Hunger", 10, 10);
   drawStatusRect(10, 10, animal.full);
   ctx.fillText("Happiness", 150, 10);
   drawStatusRect(150, 10, animal.happiness);
   ctx.fillText("Energy", 300, 10);
   drawStatusRect(300, 10, animal.energy);

   animal.draw(ctx);
   ball.draw(ctx);
   food.draw(ctx);
}

function drawStatusRect(x, y, width) {
   ctx.save();
   ctx.fillStyle = '#00f';
   ctx.fillRect(x, y, 100, 10);
   ctx.fillStyle = '#f00';
   ctx.fillRect(x, y, width, 10);
   ctx.restore();
}

function onKeyDown(evt) {
 if (evt.keyCode == 39) rightKey = true;
 else if (evt.keyCode == 37) leftKey = true;
 if (evt.keyCode == 38) upKey = true;
 else if (evt.keyCode == 40) downKey = true;
}

function onKeyUp(evt) {
 if (evt.keyCode == 39) rightKey = false;
 else if (evt.keyCode == 37) leftKey = false;
 if (evt.keyCode == 38) upKey = false;
 else if (evt.keyCode == 40) downKey = false;
}

function onMouseOver(evt) {
   goToMouse = true;
   if(evt.offsetX) {
       mx = evt.offsetX;
       my = evt.offsetY;
   }
   else if(evt.layerX) {
       mx = evt.layerX;
       my = evt.layerY;
   }
}

function clearCanvas() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
}

$(document).keydown(onKeyDown);
$(document).keyup(onKeyUp);
$("canvas").mousemove(onMouseOver);
$("canvas").mouseout(function() {goToMouse = false});

</script>

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
    <button type="submit" id="createbutton" class="btn btn-large btn-info" onClick="animal.eating();">Feed your pet</button>
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
