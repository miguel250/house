#### Development Instructions
    - Download [virtualbox](https://www.virtualbox.org/wiki/Downloads)
    - Download [Vagrant](http://downloads.vagrantup.com/)
    - Install [berkshelf](http://berkshelf.com/)
    - Install [berkshelf vagrant plugin](http://berkshelf.com/#install_the_vagrant_berkshelf_plugin)
    - run "vagrant up" in the root directory of project to create environment
    - Open http://192.168.33.10/

#### Deployment Instructions
	- Install mongo pecl
	- Run "/usr/bin/php bin/composer.phar self-update" and "/usr/bin/php bin/composer.phar install" in the root directory of project.
	- Point web server root configuration to "project-path/public"

#### Run unit tests
	- run "vagrant ssh" in the root directory of project after creating environment.
	- run cd webapp && ./core/vendor/bin/phpunit

#### Specs
##### Front End
- [ ] Create World
- [ ] Add 3D House
- [ ] Add 3D movable items
- [ ] Add 3D characters
- [ ] Send item location to backend
- [ ] Send user location to backend


##### Core API
- [x] Create user session and storage it in mongo
- [x] Keep track items locations in Mongo
- [x] Save user
- [x] Save user location
- [X] Unit Tests


##### Multiple Users
- [ ] Check For new users
- [ ] Check is users is online


#### Helpful Links
- http://jeromeetienne.github.io/tquery/ (extends three.js with jquery and it has pretty cool minecraft models)
- http://framework.zend.com/manual/2.0/en/index.html (Zend 2 Docs)
- https://github.com/zendframework/ZendSkeletonApplication] (Zend 2 App skeleton)
- https://github.com/doctrine/DoctrineMongoODMModule (Doctrine Zend Module for mongodb)
- http://learningthreejs.com/blog/2012/06/05/3d-physics-with-three-js-and-physijs/
- http://static.zend.com/topics/ZF2REST-20130404.pdf (RESTful ZF2 pdf from zf lead project manager)