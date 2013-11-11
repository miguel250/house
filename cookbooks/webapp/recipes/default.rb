include_recipe "apt"
include_recipe "build-essential"
include_recipe "git"
include_recipe "mongodb::default"

package "curl"


home_path = node['webapp']['path']


execute "chown -R vagrant:vagrant #{home_path}" do
  user "root"
  group "root"
  action "run"
end

#run mongodb on port 27017
mongodb_instance "mongodb" do
  port 27017
end


#create log directory is not there
directory "#{home_path}/logs" do
  owner user
  group group
  mode "0755"
  action :create
end

directory "#{home_path}/logs/nginx" do
  owner user
  group group
  mode "0755"
  action :create
end

package "libpcre3-dev" do
  action :install
end

#add repository with last php version
apt_repository "nginx-php" do
  uri "http://ppa.launchpad.net/ondrej/php5/ubuntu"
  distribution node['lsb']['codename']
  components ["main"]
  keyserver "keyserver.ubuntu.com"
  key "E5267A6C"
end



node.override['php-fpm']['error_log'] = "#{home_path}/logs/php-fpm.log"
node.override['php-fpm']['pool']['www']['listen'] = "/var/run/php-fpm-www.sock"
node.override['php-fpm']['pool']['www']['user']  = "vagrant"
node.override['php-fpm']['pool']['www']['group']   = "vagrant"

node.override[:php][:directives] =  {
    'date.timezone' => 'America/New_York'
}

include_recipe "php::default"
include_recipe "php-fpm::default"


php_pear_channel 'pear.php.net' do
  action :update
end

php_pear_channel 'pecl.php.net' do
  action :update
end

execute "mongo" do
    command 'pecl install mongo && echo "extension=mongo.so" | sudo tee -a /etc/php5/fpm/conf.d/mongo.ini && echo "extension=mongo.so" | sudo tee -a /etc/php5/cli/conf.d/mongo.ini'
    not_if { ::File.exists?("/etc/php5/fpm/conf.d/mongo.ini")}
    action :run
end

service "php5-fpm" do
  action :restart
end

#install nginx and add webapp settings
node.override[:nginx][:log_dir] = "#{home_path}/logs/nginx"
node.override[:nginx][:user] = "vagrant"
node.override[:nginx][:sendfile] = 'on'

include_recipe "nginx"


nginx_site "default" do
  enable false
end

execute "remove_nginx_default" do
    cwd "/home/vagrant/"
    user 'root'
    command "rm /etc/nginx/sites-available/default"
end

template "/etc/nginx/sites-available/webapp" do
  source "site.erb"
  mode 0644
end

nginx_site "webapp" do
  enable true
end

execute "/usr/bin/php bin/composer.phar install" do
    user "vagrant"
    action "run"
    cwd node['webapp']['path']
end