# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  
  #plugin
  config.berkshelf.enabled = true

  config.vm.box = "house"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  config.vm.network :private_network, ip: "192.168.33.10"

  config.ssh.forward_agent = true

  config.vm.synced_folder ".", "/home/vagrant/webapp"

  
  config.vm.provider :virtualbox do |vb|
    vb.customize ["modifyvm", :id, "--memory", "2048"]
  end
 

 
  config.vm.provision :chef_solo do |chef|
    chef.json = {
      :webapp => {
        :path => "/home/vagrant/webapp"
      }
    }
    chef.cookbooks_path = "cookbooks"
    chef.add_recipe  "mongodb::10gen_repo"
    chef.add_recipe  "webapp::default"
  end
end
