tQuery.registerStatic('MinecraftPlayer', function(opts){
	// handle arguments default value
	opts	= tQuery.extend(opts, {
		world	: tQuery.world
	});
	// 
	var world	= opts.world
	var true_player = opts.true_player

	// create all the subpart
	var character	= tQuery.createMinecraftChar();
	var controls	= tQuery.createMinecraftCharControls(character);
	var bodyAnims	= tQuery.createMinecraftCharAnimations(character);
	var headAnims	= tQuery.createMinecraftCharHeadAnimations(character);

	// getter for the subpart	
	this.character	= function(){ return character	}
	this.object3D	= function(){ return character.object3D.apply(character, arguments)	}
	this.controls	= function(){ return controls	}
	this.bodyAnims	= function(){ return bodyAnims	}
	this.headAnims	= function(){ return headAnims	}

	// handle bodyAnims based on velocity
	var previousPos	= tQuery.createVector3();
	var callback	= world.hook(function(){
		var character3D	= character.object3D();
		var velocity	= character3D.position().clone().sub(previousPos);
		if( velocity.length() ){
			bodyAnims.start('run');
			$(document.body).trigger('running');
		}else{
			bodyAnims.start('stand');
	 	}
		// update player.previousPos/player.prevRotation
		previousPos.copy( character3D.position() )
		
		// follow player with camera
		if(true_player){
			var camera = world.tCamera()
			camera.position.copy(character3D.position());
			var delta =  new THREE.Vector3(20,16,-20).normalize().multiplyScalar(3);
			var deltaLookAt =  new THREE.Vector3(0,0.1,0);
			camera.position.copy(character3D.position());
			camera.position.add(delta);
			camera.lookAt( character3D.position().clone().add(deltaLookAt) );
		}
	});
	// unhook callback on destroy
	this.addEventListener('destroy', function(){ world.unhook(callback)	});
});


// make it eventable
tQuery.MicroeventMixin(tQuery.MinecraftPlayer.prototype)

/**
 * explicit destructor
 */
tQuery.MinecraftPlayer.prototype.destroy	= function(){	
	this.dispatchEvent('destroy')
};

tQuery.registerStatic('createMinecraftPlayer', function(opts){
	return new tQuery.MinecraftPlayer(opts)
});

//////////////////////////////////////////////////////////////////////////////////
//		comment								//
//////////////////////////////////////////////////////////////////////////////////

tQuery.MinecraftPlayer.prototype.addTo = function(/* arguments */) {
	var object3D	= this.object3D();
	object3D.addTo.apply(object3D, arguments)
	return this;
};

tQuery.MinecraftPlayer.prototype.removeFrom = function(/* arguments */) {
	var object3D	= this.object3D();
	object3D.removeFrom.apply(object3D, arguments)
	return this;
};

