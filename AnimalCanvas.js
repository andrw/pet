
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
/**
 *
 * @param location
 * @param image
 * @param width
 * @param height
 */
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
var toFood = 4;
var stand = 5;
var dead = 6;

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
var deathImg = new Image();


function init() {
//window.addEventListener('keydown',doKeyDown,true);

	canvas = document.getElementById("canvas");

	imgObj = new Image();
	imgObj.src='http://images.all-free-download.com/images/graphiclarge/dog_face_cartoon_world_label_clip_art_6222.jpg';
	deathImg = new Image();
	deathImg.src = 'http://www.clipartreview.com/_images_300/Black_and_white_skull_and_crossbones_100730-220133-247009.jpg';

	foodImg.src = 'http://www.baxterboo.com/images/products/large/paw-lickers-natural-dog-treats-peanut-butter-cookies-1.jpg';
	ballImg.src = 'http://www.benutextiles.com/full-images/tennis-ball-felt-758304.jpg';

	// Create animal ------------------------------------------
	animal = new Entity(new Vector(100.0, 100.0), imgObj, 100.0, 100.0);
	animal.state = roam;
	animal.timer = 500;
	animal.full = 100.0;
	animal.dirty = 100.0;
	animal.shaggy = 100.0;
	animal.energy = 100.0;
	animal.happiness = 100.0;
	// Animal update proxy
	animal.updateCall = function() {
    	if (animal.state != dead) {
        	animal.timer -= 1;
        	if (animal.timer < 0) {
            	animal.timer = 0;
        	}

        	// Handle full/hunger
        	animal.full -= 0.01;
        	if (animal.full <= 0) {
            	animal.image = deathImg;
            	animal.setVel(new Vector(0.0, 0.0));
            	animal.full = 0;
        	}
        	if (animal.full < 10) {
            	if (food.visible) {
                	animal.state = toFood;
            	}
        	}
        	if (animal.full > 100) {
            	animal.full = 100;
        	}
        	// Handle dirtiness
        	animal.dirty -= 0.015;
        	if (animal.state == eat) {
            	animal.dirty -= 0.01
        	}

        	if (animal.dirty > 100.0) animal.dirty = 100.0;
        	if (animal.dirty < 0.0) animal.dirty = 0.0;

        	// Handle shagginess
        	animal.shaggy -= -0.01;
        	if (animal.shaggy > 100.0) animal.shaggy = 100.0;
        	if (animal.shaggy < 0.0) animal.shaggy = 0.0;

        	// Handle energy
        	if (animal.state == chase) {
            	animal.energy -= 0.05;
        	} else if (animal.state == roam) {
            	animal.energy -= 0.02;
        	} else if (animal.state == sleep) {
            	animal.energy += 0.1;
            	if (animal.timer == 0 || animal.energy >= 100) {
                	animal.state = roam;
                	animal.roam();
            	}
        	}
        	if (animal.energy < 0.0) animal.energy = 0.0;
        	if (animal.energy > 100.0) animal.energy = 100.0;


        	// Handle happiness
        	if (animal.state == chase) {
            	animal.happiness += 0.1;
        	} else if (animal.state == roam) {
            	animal.happiness -= 0.01;
        	} else if (animal.state == sleep) {
            	animal.happiness -= 0.08;
        	} else if (animal.state == eat){
            	animal.happiness += 0.05;
        	} else {
            	animal.happiness -= 0.05
        	}
        	if (animal.happiness < 0.0) {
            	animal.happiness = 0.0;
            	if (animal.timer == 0) animal.state = chase;
        	}
        	if (animal.happiness > 100.0) animal.happiness = 100.0;
    	}
	};
	// Animal roam func
	animal.roam = function() {
    	animal.setVel(new Vector(Math.random() * 2.0 - 1.0, Math.random() * 2.0 - 1.0));
	};
/*	// Animal eating handler
	animal.eating = function() {
    	animal.full = animal.full + 0.5;
    	if (animal.full > 100.0) {
        	animal.full = 100.0;
        	animal.state = roam;
        	food.visible = false;
    	}
	};*/
	// Animal groom handler
	animal.groom = function() {
    	animal.shaggy -= 10.0;
    	if (animal.shaggy < 0.0) animal.shaggy = 0.0;
	};
	// Animal clean handler
	animal.clean = function() {
    	animal.dirty += 10.0;
    	animal.happiness -= 5.0;
    	if (animal.dirty > 100.0) animal.dirty = 100.0;
    	if (animal.happiness < 0) animal.happiness = 0.0;
	};

	// Animal playing handler
	animal.play = function() {
    	if (animal.state != sleep) animal.happiness += 5.0;
    	if(animal.happiness > 100) animal.happiness = 100;
	};

	// Animal tosleep handler
	animal.sleep = function() {
    	animal.state=sleep;
    	animal.timer=500;
	};


	// Create ball ------------------------------------------
	ball = new Entity(new Vector(200.0, 200.0), ballImg, 30.0, 30.0);
	ball.setVel(new Vector(2.0, 2.0));
	ball.updateCall = function() {
    	var ballXDir = ball.velocity.x / Math.abs(ball.velocity.x);
    	var ballYDir = ball.velocity.y / Math.abs(ball.velocity.y);
    	var newXVel = (ball.velocity.x != 0) ? ball.velocity.x - ballXDir/200.0 : 0.0;
    	var newYVel = (ball.velocity.y != 0) ? ball.velocity.y - ballYDir/200.0 : 0.0;

    	ball.setVel(new Vector((Math.abs(newXVel) < .02) ? 0.0 : newXVel, (Math.abs(newYVel) < .02) ? 0.0 : newYVel));

	};
	// Ball click handler
	ball.click = function() {
    	ball.setVel(new Vector(Math.random() * 8.0 - 4.0, Math.random() * 8.0 - 4.0));
    	animal.play();
	};

	// Create food ------------------------------------------
	food = new Entity(new Vector(400.0, 100.0), foodImg, 50.0, 50.0);
	food.visible = false;
	// Food creation handler
	food.createFood = function() {
    	if (!food.visible) {
        	food.location.x = Math.random() * (canvas.width - 200) + 100;
        	food.location.y = Math.random() * (canvas.height - 200) + 100;
        	food.visible = true;
    	}
    	if (animal.timer == 0 || animal.full < 10) {
        	animal.state = toFood;
    	}
	};


	animal.setVel(new Vector(1, 1));

	ctx = canvas.getContext("2d");


	setInterval("update()", 1000/100);
	setInterval("draw()", 1000/60);
	//setInterval("ball.click()", 3000);
}

function randomize() {
	dx+= 2*((Math.random()-.5)-(dx/20));
	dy+= 2*((Math.random()-.5)-(dy/20));
}

function update() {
	if (animal.state != sleep && (ball.velocity.x != 0 || ball.velocity.y != 0 && animal.timer == 0)) {
    	animal.state = chase;
	}

	// Handle emergency sleep!
	if (animal.energy < 2.0){
    	animal.state = sleep;
    	animal.timer = 1000;
	}
	// Handle chasing state
	if (animal.state == chase) {
    	xDelta = -animal.location.x + ball.location.x;
    	yDelta = -animal.location.y + ball.location.y;

    	if (Math.abs(xDelta) < animal.width/2 && Math.abs(yDelta) < animal.height/2) {
        	animal.state = roam;
        	animal.roam();
        	animal.timer = 300;
    	} else {
        	animal.setVel(new Vector(xDelta / Math.abs(xDelta),
            	yDelta / Math.abs(yDelta)));
    	}
	// Handle walking to food
	} else if (animal.state == toFood) {
    	xDelta = -animal.location.x + food.location.x;
    	yDelta = -animal.location.y + food.location.y;

    	if (Math.abs(xDelta) < animal.width/2 && Math.abs(yDelta) < animal.height/2) {
        	animal.state = eat;
        	animal.timer = 500;
        	animal.setVel(new Vector(0.0, 0.0));
    	} else {
        	animal.setVel(new Vector(xDelta / Math.abs(xDelta),
            	yDelta / Math.abs(yDelta)));
    	}

	// Handle eating state
	} else if (animal.state == eat) {
    	if (animal.timer > 0) {
        	animal.full += .1;
    	} else {
        	animal.state = roam;
        	animal.roam();
        	animal.timer = 300;
        	food.visible = false;
    	}
	} else if (animal.state == sleep) {
    	animal.setVel(new Vector(0.0, 0.0));
	}
	animal.update();
	ball.update();
}

// Draws all objects on the canvas
function draw() {
	ctx.restore();
	clearCanvas();
	ctx.save();

	ctx.translate(15, 10);
	ctx.fillText("Hunger", 0,0);
	drawStatusRect(0, 3, animal.full);

	ctx.translate(150, 0);
	ctx.fillText("Happiness", 0, 0);
	drawStatusRect(0, 3, animal.happiness);

	ctx.translate(150, 0);
	ctx.fillText("Energy", 0, 0);
	drawStatusRect(0, 3, animal.energy);

	ctx.translate(150, 0);
	ctx.fillText("Hygeine", 0, 0);
	drawStatusRect(0, 3, animal.dirty);

	ctx.restore();

	ctx.fillText(animal.timer  + "", 100, 100);
	animal.draw(ctx);
	ball.draw(ctx);
	if (food.visible) food.draw(ctx);
}

function drawStatusRect(x, y, width) {
	ctx.save();
	ctx.fillStyle = '#fff';
	ctx.strokeRect(x-1, y-1, 102, 12);
	if (width > 20) ctx.fillStyle = '#0f0';
	else ctx.fillStyle = '#f00';
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