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
    var world  = tQuery.createWorld().boilerplate({cameraControls: false, stats:false}).start();


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
    var player  = tQuery.createMinecraftPlayer({'true_player': true})
    player.addTo(world);
    player.object3D().position(user.position_x, user.position_y, user.position_z);
    user.player = player;

   var online_players = function(){
        $.each(users_online, function(key, value){
            var online_player = tQuery.createMinecraftPlayer()
            online_player.addTo(world);
            online_player.object3D().position(value.position_x, value.position_y, value.position_z);
            users_online[key].character = online_player;

        });
   };

   online_players();

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
    
    var create_items = function(){
        $.each(items_list, function(key, value){
            var cube  = tQuery.createCube(.4,.4,.4);
            cube.addTo(world);
            cube.position( value.position_x, value.position_y, value.position_z);
            cube.setLambertMaterial().map(cTexture).back()
            cube.castShadow(true);
            items_list[key].cube = cube;

        });
    };

    create_items();



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
                var cube_z = selectedCube.cube.position().z;
                var cube_x = selectedCube.cube.position().x;
                selectedCube.cube.position(cube_x, 0.7, cube_z);
                save_item_position();
                selectedCube = null;
                console.log("drop!");
            }
        }
    };

    window.onkeydown = function(e) {
        if(e.keyCode==32){
            $.each(items_list, function(key, value){
                var cube = value.cube;
                var distance = cube.position().distanceToSquared(player.object3D().position());
                if(distance>=0.3 && distance <= 1.53){
                    var cube_z = cube.position().z;
                    var cube_x = cube.position().x;
                    cube.position(cube_x, 0.9, cube_z);
                    selectedCube = value;
                    console.log("Picked!");
                    return false;
                }
                
            });
        }
    }

    $(document.body).on('running', function(){

        var player_x = player.object3D().position().x;
        var player_z = player.object3D().position().z;
        var player_y = player.object3D().position().y;

        user.position_x = player_x;
        user.position_z = player_z;
        user.position_y = player_y;

        if(selectedCube !== null){
            var cube_z = selectedCube.cube.position().z;
            var cube_x = selectedCube.cube.position().x;

            selectedCube.position_x = player_x;
            selectedCube.position_z = player_z+.4;

            selectedCube.cube.position(player_x, 0.9, player_z+.4);
            
        }
    });

    

    (function update_user(){
        var post_data = jQuery.extend(true, {}, user);

        if(selectedCube !== null){
            post_data.item = {'id': selectedCube.id}
        }else{
            post_data.item = "null";
        }

        delete post_data.player;

        $.ajax({ 
            url: "/api/user/"+user.id,
            type : 'PATCH',
            data : post_data,
            success: function(data){
                console.log("position saved!!");
            }, 
            dataType: "json", 
            complete: update_user, 
            timeout: 10000 
        });
    })();

    (function update_data(){

        $.ajax({ 
            url: "/api/ping?disconnected=true",
            type : 'GET',
            cache:false,
            success: function(data){
                console.log("update users data");

                $.each(data.items, function(key, value){
                    if(items_list[key] !== undefined){
                        if(selectedCube !== null && selectedCube.id === key){
                            return;
                        }
                        items_list[key].position_z = value.position_z;
                        items_list[key].position_x = value.position_x;
                        items_list[key].position_y = value.position_y;

                        items_list[key].cube.position(value.position_x, value.position_y, value.position_z);
                    }else{
                        if(items_list instanceof Array){
                            items_list = {}
                        }

                        items_list[key] = value;
                        var cube  = tQuery.createCube(.4,.4,.4);
                        cube.addTo(world);
                        cube.position( value.position_x, value.position_y, value.position_z);
                        cube.setLambertMaterial().map(cTexture).back()
                        cube.castShadow(true);
                        items_list[key].cube = cube;
                    }
                });

                $.each(data.users, function(key, value){
                    if(users_online[key] !== undefined && users_online[key].character !== undefined){
                        users_online[key].position_z = value.position_z;
                        users_online[key].position_x = value.position_x;
                        users_online[key].position_y = value.position_y;
                        users_online[key].character.object3D().position(value.position_x, value.position_y, value.position_z);
                    }else{
                        if(users_online instanceof Array){
                            users_online = {}
                        }
                       users_online[key] = value
                       var online_player = tQuery.createMinecraftPlayer();
                       online_player.addTo(world);
                       online_player.object3D().position(value.position_x, value.position_y, value.position_z);
                       users_online[key].character = online_player;
                    }
                });
                $.each(data.disconnected, function(key, value){
                    if(users_online[value] !== undefined && users_online[value].character !== undefined){
                       users_online[value].character.removeFrom(world);
                       delete users_online[value]
                    }
                });

            }, 
            dataType: "json", 
            complete: update_data, 
            timeout: 10000 
        });
    })();

    var save_item_position = function(){
        if(selectedCube !== null){
            var post_data = jQuery.extend(true, {}, selectedCube);

            delete post_data.cube;

            $.ajax({ 
                url: "/api/item/"+selectedCube.id,
                type : 'PATCH',
                data : post_data,
                success: function(data){
                    console.log("Item saved!!");
                }, 
                dataType: "json", 
                complete: save_item_position,
                timeout: 30000 
            });
        }
    };

    (function update_item(){
        console.log("Checking items!!");
        save_item_position();
        setTimeout(update_item,10000);
    })();

});