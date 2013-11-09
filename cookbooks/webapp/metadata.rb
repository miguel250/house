maintainer       "Miguel Perez"
maintainer_email "miguel@miguelpz.com"
license          "Apache 2.0"
description      "Webapp cookbook"
version          "0.0.1"
name             "Concurrents"
provides         "Miguel Perez"

recipe "", ""

depends "build-essential"
depends "apt"
depends "git"
depends "nginx"
depends "php"
depends "php-fpm"
depends "mongodb"

%w{ debian ubuntu centos redhat smartos }.each do |os|
    supports os
end