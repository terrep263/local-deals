(function (cjs, an) {

var p; // shortcut to reference prototypes
var lib={};var ss={};var img={};
lib.ssMetadata = [];


// symbols:



(lib.flower = function() {
	this.initialize(img.flower);
}).prototype = p = new cjs.Bitmap();
p.nominalBounds = new cjs.Rectangle(0,0,450,528);// helper functions:

function mc_symbol_clone() {
	var clone = this._cloneProps(new this.constructor(this.mode, this.startPosition, this.loop));
	clone.gotoAndStop(this.currentFrame);
	clone.paused = this.paused;
	clone.framerate = this.framerate;
	return clone;
}

function getMCSymbolPrototype(symbol, nominalBounds, frameBounds) {
	var prototype = cjs.extend(symbol, cjs.MovieClip);
	prototype.clone = mc_symbol_clone;
	prototype.nominalBounds = nominalBounds;
	prototype.frameBounds = frameBounds;
	return prototype;
	}


(lib.shine = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.lf(["rgba(255,255,255,0.4)","rgba(255,255,255,0)"],[0,1],0,-205,0,205.1).s().p("EgYoAgDQASgSPA/dQO+/dA5g5IPAAAQBSAAA7A7QA7A7AABSMAAAA89g");
	this.shape.setTransform(157.7,205.1);

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(1));

}).prototype = getMCSymbolPrototype(lib.shine, new cjs.Rectangle(0,0,315.4,410.2), null);


(lib.sgfsegseg = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer_1
	this.instance = new lib.flower();
	this.instance.parent = this;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(1));

}).prototype = getMCSymbolPrototype(lib.sgfsegseg, new cjs.Rectangle(0,0,450,528), null);


(lib.Tween2 = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{});

	// Layer 1
	this.shape = new cjs.Shape();
	this.shape.graphics.lf(["#000000","#57595C"],[0,1],0,-3.7,0,3.6).s().p("Az4AkQgPAAgKgKQgLgLAAgPQAAgOALgLQAKgKAPAAMAnwAAAQAPAAALAKQALALAAAOQAAAPgLALQgLAKgPAAg");
	this.shape.setTransform(154.7,146.1,0.616,0.727);

	this.shape_1 = new cjs.Shape();
	this.shape_1.graphics.f("#FFFF66").s().p("AgGAIQgEgEAAgEQAAgDAEgEQADgDADAAQAFAAADADQADAEAAADQAAAEgDAEQgDADgFAAQgDAAgDgDg");
	this.shape_1.setTransform(-233.9,146,0.616,0.727);

	this.shape_2 = new cjs.Shape();
	this.shape_2.graphics.f("#151116").s().p("AgTAVQgJgJAAgMQAAgLAJgJQAIgIALAAQAMAAAIAIQAJAJAAALQAAAMgJAJQgIAIgMAAQgLAAgIgIgAgRgQQgHAGAAAKQAAAKAHAIQAIAHAJAAQALAAAHgHQAHgIAAgKQAAgKgHgGQgHgIgLAAQgJAAgIAIg");
	this.shape_2.setTransform(-233.7,147,0.616,0.727);

	this.shape_3 = new cjs.Shape();
	this.shape_3.graphics.f("#FF9900").s().p("AgTAVQgJgJAAgMQAAgLAJgJQAIgIALAAQAMAAAIAIQAJAJAAALQAAAMgJAJQgIAIgMAAQgLAAgIgIg");
	this.shape_3.setTransform(-233.7,146.9,0.616,0.727);

	this.shape_4 = new cjs.Shape();
	this.shape_4.graphics.f("#151116").s().p("AgfAfQgNgNABgSQgBgRANgOQANgMASAAQASAAANAMQANAOABARQgBASgNANQgNAOgSAAQgSAAgNgOgAgagaQgLALAAAPQAAAQALALQALAMAPAAQAQAAAMgMQALgLgBgQQABgPgLgLQgMgMgQAAQgPAAgLAMg");
	this.shape_4.setTransform(-233.7,147,0.616,0.727);

	this.shape_5 = new cjs.Shape();
	this.shape_5.graphics.f("#221F24").s().p("AgfAfQgNgNABgSQgBgRANgOQANgMASAAQASAAANAMQANAOABARQgBASgNANQgNAOgSAAQgSAAgNgOgAgagaQgLALAAAPQAAAQALALQALAMAPAAQAQAAAMgMQALgLgBgQQABgPgLgLQgMgMgQAAQgPAAgLAMg");
	this.shape_5.setTransform(-233.7,147,0.616,0.727);

	this.shape_6 = new cjs.Shape();
	this.shape_6.graphics.lf(["#4C4B4F","#221F24"],[0,1],0,3.5,0,-3.5).s().p("AgcAeQgNgNAAgRQAAgQANgNQAMgMAQABQASgBALAMQANANAAAQQAAARgNANQgLAMgSgBQgQABgMgMg");
	this.shape_6.setTransform(-233.7,147,0.616,0.727);

	this.shape_7 = new cjs.Shape();
	this.shape_7.graphics.lf(["#655231","#3A3223"],[0,1],0,-1,0,1).s().p("AgGAIQgEgEAAgEQAAgDAEgEQADgDADAAQAFAAADADQADAEAAADQAAAEgDAEQgDADgFAAQgDAAgDgDg");
	this.shape_7.setTransform(-223.5,146.1,0.616,0.727);

	this.shape_8 = new cjs.Shape();
	this.shape_8.graphics.f("#151116").s().p("AgUAVQgIgJAAgMQAAgLAIgJQAJgIALAAQAMAAAIAIQAJAJAAALQAAAMgJAJQgIAIgMAAQgLAAgJgIgAgQgQQgIAGAAAKQAAAKAIAIQAGAHAKAAQAKAAAIgHQAHgIAAgKQAAgKgHgGQgIgIgKAAQgKAAgGAIg");
	this.shape_8.setTransform(-223.3,147,0.616,0.727);

	this.shape_9 = new cjs.Shape();
	this.shape_9.graphics.f("#151116").s().p("AgUAVQgIgJAAgMQAAgLAIgJQAJgIALAAQAMAAAIAIQAJAJAAALQAAAMgJAJQgIAIgMAAQgLAAgJgIg");
	this.shape_9.setTransform(-223.3,147,0.616,0.727);

	this.shape_10 = new cjs.Shape();
	this.shape_10.graphics.f("#151116").s().p("AgfAfQgNgNABgSQgBgRANgOQAOgMARAAQATAAAMAMQAOAOAAARQAAASgOANQgMAOgTAAQgRAAgOgOgAgagaQgMALAAAPQAAAQAMALQALAMAPAAQAQAAALgMQALgLABgQQgBgPgLgLQgLgMgQAAQgPAAgLAMg");
	this.shape_10.setTransform(-223.3,147,0.616,0.727);

	this.shape_11 = new cjs.Shape();
	this.shape_11.graphics.f("#221F24").s().p("AgfAfQgNgNABgSQgBgRANgOQAOgMARAAQATAAAMAMQAOAOAAARQAAASgOANQgMAOgTAAQgRAAgOgOgAgagaQgMALAAAPQAAAQAMALQALAMAPAAQAQAAALgMQALgLABgQQgBgPgLgLQgLgMgQAAQgPAAgLAMg");
	this.shape_11.setTransform(-223.3,147,0.616,0.727);

	this.shape_12 = new cjs.Shape();
	this.shape_12.graphics.lf(["#4C4B4F","#221F24"],[0,1],0,3.5,0,-3.5).s().p("AgdAeQgLgNgBgRQABgQALgNQANgMAQABQASgBALAMQAMANAAAQQAAARgMANQgLAMgSgBQgQABgNgMg");
	this.shape_12.setTransform(-223.3,147,0.616,0.727);

	this.shape_13 = new cjs.Shape();
	this.shape_13.graphics.lf(["#626367","#F7F7F8","#C8CACE","#CBCDD1","#D8DADC","#DCDEE0","#D8DADC","#CBCDD1","#C8CACE","#F7F7F8","#626367"],[0,0.012,0.051,0.09,0.298,0.498,0.702,0.91,0.949,0.988,1],421.7,0,-421.6,0).s().p("EhB4AB4IAAjvMCDwAAAIAADvg");
	this.shape_13.setTransform(0,146.3,0.616,0.727);

	this.shape_14 = new cjs.Shape();
	this.shape_14.graphics.lf(["#AAACB0","#57595C","#202020"],[0,0.686,1],0,-6.6,0,6.7).s().p("Eg8mABDQhUAAiHhDIh3hCMCDwAAAQgzAihFAgQiNBDhcAAg");
	this.shape_14.setTransform(0,159.8,0.616,0.727);

	this.shape_15 = new cjs.Shape();
	this.shape_15.graphics.f().s("#4E4F53").ss(0.5,0,0,4).p("AgqAMQAFAFAHAAIA9AAQAHAAAFgFQAFgFAAgHQAAgGgFgFQgFgFgHAAIg9AAQgHAAgFAFQgFAFAAAGQAAAHAFAFg");
	this.shape_15.setTransform(17.4,-152.2,0.587,0.727);

	this.shape_16 = new cjs.Shape();
	this.shape_16.graphics.lf(["#57595C","#202020"],[0,1],0,1.6,0,-1.7).s().p("AgeARQgHAAgFgFQgFgFAAgHQAAgGAFgFQAFgFAHAAIA9AAQAHAAAFAFQAFAFAAAGQAAAHgFAFQgFAFgHAAg");
	this.shape_16.setTransform(17.4,-152.2,0.587,0.727);

	this.shape_17 = new cjs.Shape();
	this.shape_17.graphics.f().s("#4E4F53").ss(0.5,0,0,4).p("AAfgQIg9AAQgHAAgFAFQgFAFAAAGQAAAHAFAFQAFAFAHAAIA9AAQAHAAAFgFQAFgFAAgHQAAgGgFgFQgFgFgHAAg");
	this.shape_17.setTransform(-18.6,-152.2,0.587,0.727);

	this.shape_18 = new cjs.Shape();
	this.shape_18.graphics.lf(["#57595C","#202020"],[0,1],0,1.6,0,-1.7).s().p("AgeARQgHAAgFgFQgFgFAAgHQAAgGAFgFQAFgFAHAAIA9AAQAHAAAFAFQAFAFAAAGQAAAHgFAFQgFAFgHAAg");
	this.shape_18.setTransform(-18.6,-152.2,0.587,0.727);

	this.shape_19 = new cjs.Shape();
	this.shape_19.graphics.lf(["#7A7D82","#4D4F54"],[0,1],0,-1.7,0,1.8).s().p("AgMANQgEgGAAgHQAAgGAEgFQAGgFAGgBQAHABAFAFQAGAFAAAGQAAAHgGAGQgFAEgHAAQgGAAgGgEg");
	this.shape_19.setTransform(-0.4,-151.8,0.387,0.727);

	this.shape_20 = new cjs.Shape();
	this.shape_20.graphics.lf(["#000000","#484A4D"],[0,1],0,-3.1,0,3.1).s().p("AgVAWQgJgJAAgNQAAgMAJgJQAJgJAMAAQANAAAJAJQAJAJAAAMQAAANgJAJQgJAJgNAAQgMAAgJgJg");
	this.shape_20.setTransform(-0.4,-152.5,0.387,0.727);

	this.shape_21 = new cjs.Shape();
	this.shape_21.graphics.f().s("#4E4F53").ss(0.5,0,0,4).p("AERAAQAAAXgRAPQgQAQgWAAIm0AAQgWAAgPgQQgRgPAAgXQAAgVARgQQAPgQAWAAIG0AAQAWAAAQAQQARAQAAAVg");
	this.shape_21.setTransform(-0.1,-152.5,0.387,0.727);

	this.shape_22 = new cjs.Shape();
	this.shape_22.graphics.lf(["#57595C","#202020"],[0,1],0,-5.4,0,5.4).s().p("AjZA2QgXAAgPgQQgRgPABgXQgBgVARgQQAPgQAXAAIGzAAQAWAAARAQQAPAQAAAVQAAAXgPAPQgRAQgWAAg");
	this.shape_22.setTransform(-0.1,-152.5,0.387,0.727);

	this.instance = new lib.shine();
	this.instance.parent = this;
	this.instance.setTransform(203.3,-11.5,0.597,0.727,0,0,0,315.1,205.1);
	this.instance.alpha = 0.648;

	this.shape_23 = new cjs.Shape();
	this.shape_23.graphics.f("#000000").s().p("EgwlAgDMAAAg89QAAhSA7g7QA7g7BSAAMBa7AAAQBTAAA7A7QA6A7AABSMAAAA89g");
	this.shape_23.setTransform(0,-11.5,0.654,0.727);

	this.shape_24 = new cjs.Shape();
	this.shape_24.graphics.lf(["#2A2B2E","#040506"],[0,1],0,-206.6,0,206.7).s().p("EgxGAgTMAAAg9dQABhSA6g7QA7g7BTAAMBb8AAAQBSAAA7A7QA6A7ABBSMAAAA9dg");
	this.shape_24.setTransform(0.1,-12.5,0.654,0.727);

	this.shape_25 = new cjs.Shape();
	this.shape_25.graphics.lf(["#DCDDE0","#CBCCD1"],[0,1],0,-208,0,208).s().p("EgxkAggMAAAg93QAAhTA7g6QA6g7BTAAMBc5AAAQBTAAA7A7QA6A6AABTMAAAA93g");
	this.shape_25.setTransform(0.2,-13.6,0.654,0.727);

	this.timeline.addTween(cjs.Tween.get({}).to({state:[{t:this.shape_25},{t:this.shape_24},{t:this.shape_23},{t:this.instance},{t:this.shape_22},{t:this.shape_21},{t:this.shape_20},{t:this.shape_19},{t:this.shape_18},{t:this.shape_17},{t:this.shape_16},{t:this.shape_15},{t:this.shape_14},{t:this.shape_13},{t:this.shape_12},{t:this.shape_11},{t:this.shape_10},{t:this.shape_9},{t:this.shape_8},{t:this.shape_7},{t:this.shape_6},{t:this.shape_5},{t:this.shape_4},{t:this.shape_3},{t:this.shape_2},{t:this.shape_1},{t:this.shape}]}).wait(1));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(-259.8,-164.7,519.6,329.4);


// stage content:
(lib.laptop_video_hd_Canvas = function(mode,startPosition,loop) {
	this.initialize(mode,startPosition,loop,{step0:19,step1:21,step2:23,step3:25,step4:27,step5:29,step6:31,step7:33,step8:35,step9:37,step10:39});

	// timeline functions:
	this.frame_19 = function() {
		this.stop();
	}
	this.frame_21 = function() {
		this.stop();
	}
	this.frame_23 = function() {
		this.stop();
	}
	this.frame_25 = function() {
		this.stop();
	}
	this.frame_27 = function() {
		this.stop();
	}
	this.frame_29 = function() {
		this.stop();
	}
	this.frame_31 = function() {
		this.stop();
	}
	this.frame_33 = function() {
		this.stop();
	}
	this.frame_35 = function() {
		this.stop();
	}
	this.frame_37 = function() {
		this.stop();
	}
	this.frame_39 = function() {
		this.stop();
	}

	// actions tween:
	this.timeline.addTween(cjs.Tween.get(this).wait(19).call(this.frame_19).wait(2).call(this.frame_21).wait(2).call(this.frame_23).wait(2).call(this.frame_25).wait(2).call(this.frame_27).wait(2).call(this.frame_29).wait(2).call(this.frame_31).wait(2).call(this.frame_33).wait(2).call(this.frame_35).wait(2).call(this.frame_37).wait(2).call(this.frame_39).wait(7));

	// Layer 23
	this.instance = new lib.shine();
	this.instance.parent = this;
	this.instance.setTransform(757.5,160.3,1.156,1.214,0,0,0,315.1,205);
	this.instance.alpha = 0.16;
	this.instance._off = true;

	this.timeline.addTween(cjs.Tween.get(this.instance).wait(15).to({_off:false},0).wait(31));

	// Layer 24 (mask)
	var mask = new cjs.Shape();
	mask._off = true;
	var mask_graphics_15 = new cjs.Graphics().p("Eg5vAh7MgABhD1MBzhAAAMAAABD1g");

	this.timeline.addTween(cjs.Tween.get(mask).to({graphics:null,x:0,y:0}).wait(15).to({graphics:mask_graphics_15,x:368.1,y:154.6}).wait(31));

	// Layer 26
	this.shape = new cjs.Shape();
	this.shape.graphics.rf(["#1A1A1A","#191919","#191919"],[0,0.761,1],-367.8,-213.7,0,-367.8,-213.7,846.1).s().p("Eg5vAh7MgABhD1MBzhAAAMAAABD1g");
	this.shape.setTransform(368.1,154.6);
	this.shape._off = true;

	var maskedShapeInstanceList = [this.shape];

	for(var shapedInstanceItr = 0; shapedInstanceItr < maskedShapeInstanceList.length; shapedInstanceItr++) {
		maskedShapeInstanceList[shapedInstanceItr].mask = mask;
	}

	this.timeline.addTween(cjs.Tween.get(this.shape).wait(15).to({_off:false},0).wait(31));

	// Layer 27
	this.instance_1 = new lib.Tween2("synched",0);
	this.instance_1.parent = this;
	this.instance_1.setTransform(366.3,178.9,1.936,1.671);
	this.instance_1.alpha = 0;

	this.timeline.addTween(cjs.Tween.get(this.instance_1).to({alpha:1},15).wait(31));

	// Layer_1
	this.instance_2 = new lib.sgfsegseg();
	this.instance_2.parent = this;
	this.instance_2.setTransform(877,190,1,1,0,0,0,225,264);
	this.instance_2.alpha = 0;

	this.timeline.addTween(cjs.Tween.get(this.instance_2).to({alpha:1},8).wait(38));

}).prototype = p = new cjs.MovieClip();
p.nominalBounds = new cjs.Rectangle(398.4,228.7,1238.7,550.4);
// library properties:
lib.properties = {
	id: 'F37011B06BA23746A9B7752BCAE463B8',
	width: 1070,
	height: 650,
	fps: 24,
	color: "#FFFFFF",
	opacity: 1.00,
	manifest: [
		{src:"images/flower.png", id:"flower"}
	],
	preloads: []
};



// bootstrap callback support:

(lib.Stage = function(canvas) {
	createjs.Stage.call(this, canvas);
}).prototype = p = new createjs.Stage();

p.setAutoPlay = function(autoPlay) {
	this.tickEnabled = autoPlay;
}
p.play = function() { this.tickEnabled = true; this.getChildAt(0).gotoAndPlay(this.getTimelinePosition()) }
p.stop = function(ms) { if(ms) this.seek(ms); this.tickEnabled = false; }
p.seek = function(ms) { this.tickEnabled = true; this.getChildAt(0).gotoAndStop(lib.properties.fps * ms / 1000); }
p.getDuration = function() { return this.getChildAt(0).totalFrames / lib.properties.fps * 1000; }

p.getTimelinePosition = function() { return this.getChildAt(0).currentFrame / lib.properties.fps * 1000; }

an.bootcompsLoaded = an.bootcompsLoaded || [];
if(!an.bootstrapListeners) {
	an.bootstrapListeners=[];
}

an.bootstrapCallback=function(fnCallback) {
	an.bootstrapListeners.push(fnCallback);
	if(an.bootcompsLoaded.length > 0) {
		for(var i=0; i<an.bootcompsLoaded.length; ++i) {
			fnCallback(an.bootcompsLoaded[i]);
		}
	}
};

an.compositions = an.compositions || {};
an.compositions['F37011B06BA23746A9B7752BCAE463B8'] = {
	getStage: function() { return exportRoot.getStage(); },
	getLibrary: function() { return lib; },
	getSpriteSheet: function() { return ss; },
	getImages: function() { return img; }
};

an.compositionLoaded = function(id) {
	an.bootcompsLoaded.push(id);
	for(var j=0; j<an.bootstrapListeners.length; j++) {
		an.bootstrapListeners[j](id);
	}
}

an.getComposition = function(id) {
	return an.compositions[id];
}



})(createjs = createjs||{}, AdobeAn = AdobeAn||{});
var createjs, AdobeAn;