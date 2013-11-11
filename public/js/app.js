var baseURL = document.URL;

requirejs.config({
    paths   : {
        "build"  :  baseURL+'/js/vendor/tquery',
        "plugins"   : baseURL+'/js/vendor/tquery/plugins/',
        "threex"    : baseURL+'/js/vendor/threex',
        "three.js"  : baseURL+'/js/vendor/threejs',
    },
});

require(['tquery.loaders', 'tquery.skymap', 'tquery.grassground',  'tquery.shadowmap', 'tquery.physics',
    'tquery.minecraft', 'tquery.keyboard', 'tquery.domevent'], function(){

    "use strict"
    
    var selectedCube = null;

    //Create scene
    var world  = tQuery.createWorld().boilerplate({cameraControls: false}).start();


    world.tCamera().lookAt( world.tScene().position );
    world.tRenderer().shadowMapEnabled   = true;
    world.tRenderer().shadowMapSoft       = true;
    world.tRenderer().setClearColorHex( 0xffffff, 1 );
    world.enablePhysics({
        pathWorker  : '/js/vendor/physijs/physijs_worker.js',
    });


    //Light direction            
    var ligh_direction = tQuery.createDirectionalLight();
    ligh_direction.addTo(world);
    ligh_direction.position(20, 40, -15).color(0xffffff);
    ligh_direction.castShadow(true);
    ligh_direction.shadowMap(512*2,512*2);
    ligh_direction.shadowCamera(60, -60, 60, -60, 20, 200);
    ligh_direction.shadowDarkness(0.7);
    ligh_direction.shadowBias(.002);

    //Create ground
    var texture = THREE.ImageUtils.loadTexture( "/js/vendor/tquery/plugins/assets/images/rocks.jpg" );
    texture.wrapS  = texture.wrapT = THREE.RepeatWrapping;
    texture.repeat.set( 100, 100 );
                
    var ground  = tQuery.createCube(100, 1, 100);
    ground.addTo(world);
    ground.position(0, 0, 0);
    ground.setLambertMaterial().map(texture).back();
    ground.receiveShadow(true)
    
    ground.enablePhysics({
        mass: 0
    });


    //Add Player
    var player  = tQuery.createMinecraftPlayer()
    player.addTo(world);
    player.object3D().position( 5.878401510630915,0.5,-1.0869647027003655);

    world.hook(function(){
        var keyboard    = tQuery.keyboard();
        var input   = player.controls().input();
        input.right = keyboard.pressed('right');
        input.up    = keyboard.pressed('up');
        input.left  = keyboard.pressed('left');
        input.down  = keyboard.pressed('down');
    });

    

    //Create cubes
    var cTexture    = THREE.ImageUtils.loadTexture( "/js/vendor/tquery/plugins/assets/images/plywood.jpg" );
    var cube  = tQuery.createCube(.4,.4,.4);
    cube.addTo(world);
    cube.position( 5.878401510630915,0.7,-1.0869647027003655);
    cube.setLambertMaterial().map(cTexture).back()
    cube.castShadow(true)




    //Walls
    var texture = THREE.ImageUtils.loadTexture("/js/vendor/tquery/plugins/assets/images/plywood.jpg");
    texture.wrapS  = texture.wrapT = THREE.RepeatWrapping;
    texture.repeat.set( 0.5, 0.5 );
    

    var right_wall = tQuery.createCube(20, 3, .5).addTo(world);
    right_wall.setBasicMaterial().map(texture).back();
    right_wall.position(1,0.5,2)
                       
    var top_wall = tQuery.createCube(10, 3, .5).addTo(world);
    top_wall.setBasicMaterial().map(texture).back();
    top_wall.position(11,0.5,-3).rotation(0,1.6,0);top_wall

    var middle_wall = tQuery.createCube(15, 3, .1).addTo(world);
    middle_wall.setBasicMaterial().map(texture).back();
    middle_wall.position(-.1,0.5,-3.2);

    var right_middle_wall = tQuery.createCube(4, 3, .5).addTo(world);
    right_middle_wall.setBasicMaterial().map(texture).back();
    right_middle_wall.position(1,0.5,-0.2).rotation(0,1.5,0);

    var left_middle_wall = tQuery.createCube(4, 3, .5).addTo(world);
    left_middle_wall.setBasicMaterial().map(texture).back();
    left_middle_wall.position(1,0.5,-6).rotation(0,1.5,0);
                        
    var bottom_wall  = tQuery.createCube(10, 3, .5).addTo(world);
    bottom_wall.setBasicMaterial().map(texture).back();
    bottom_wall.position(-8.41,0.5,-3).rotation(0,-1.6,0);


    var right_wall =  tQuery.createCube(20, 3, .5).addTo(world);
    right_wall.setBasicMaterial().map(texture).back()
    right_wall.position(1,0.5,-8);


    //Even handlers
    window.onkeyup = function(e) {
        if(e.keyCode==32){
            if(selectedCube !== null){
                var cube_z = selectedCube.position().z;
                var cube_x = selectedCube.position().x;
                selectedCube.position(cube_x, 0.7, cube_z);
                selectedCube = null;
                console.log("drop!");
            }
        }
    };

    window.onkeydown = function(e) {
        if(e.keyCode==32){
            var distance = cube.position().distanceToSquared(player.object3D().position());
            if(distance>=0.3 && distance <= 1.53){
                var cube_z = cube.position().z;
                var cube_x = cube.position().x;
                cube.position(cube_x, 0.9, cube_z);
                selectedCube = cube;
                console.log("Picked!");
            }
        }
    }

    $(document.body).on('running', function(){
        if(selectedCube !== null){
            var cube_z = selectedCube.position().z;
            var cube_x = selectedCube.position().x;

            var play_x = player.object3D().position().x;
            var play_z = player.object3D().position().z;
            var play_y = player.object3D().position().y;
            console.log(play_z);
            selectedCube.position(play_x, 0.9, play_z+.4);
            
        }
    });

});