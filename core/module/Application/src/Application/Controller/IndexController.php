<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Application\Document\User;
use Application\Document\Item;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	$data = array();
        $container = new Container('house');
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        
        if($container->init !== 1){
        	$user = new User();

        	$dm->persist($user);
        	$dm->flush();

        	$container->init = 1;
        	$container->userId = $user->getId();
        	$data['items'] =  array();
        }else{
        	$user = $dm->getRepository('Application\Document\User')->find($container->userId);
        	$user->setLastUpdated();
        	$user->setIsOnline(true);
        	$dm->flush();
        }

        $data['id'] = $user->getId();
        $data['is_online'] = $user->getIsOnline();
        $data['last_updated'] = $user->getLastUpdated()->getTimestamp();
        $data['position_x'] = $user->getPositionX();
        $data['position_y'] = $user->getPositionY();
        $data['position_z'] = $user->getPositionZ();

        $items_obj = $dm->getRepository('Application\Document\Item')->findAll();
        $items = array();
        
        $items = array();
        $onlineUsers = array();
        
        if(count($items_obj)!= 0){

            foreach ($items_obj as $key => $item) {
            	$key = $item->getId();
                $items[$key]["id"] = $item->getId();
                $items[$key]["name"] = $item->getName();
                $items[$key]['position_x'] = $item->getPositionX();
                $items[$key]['position_y'] = $item->getPositionY();
                $items[$key]['position_z'] = $item->getPositionZ();
            }
        }else{
        	$position = 0.2;
        	for ($create_count = 4; $create_count > 0; $create_count--) { 
        		$item = new Item();
        		$item->setPositionX(5.87840151063091+$position);
        		$item->setPositionX(0.7);
        		$item->getPositionZ(-1.0869647027003655);
        		$position += 0.1;
                $dm->persist($item);
                $dm->flush();
                $key = $item->getId();
                $item[$key]['id' ] = $key;
                $items[$key]["name"] = $item->getName();
                $items[$key]['position_x'] = $item->getPositionX();
                $items[$key]['position_y'] = $item->getPositionY();
                $items[$key]['position_z'] = $item->getPositionZ();
                
                
        	}
        }

        $users = $dm->getRepository('Application\Document\User')->findByisOnline(true);
        
        foreach ($users as $key => $user) {
        	$now = new \DateTime("-5 seconds");
            $time = $now > $user->getLastUpdated();
            if($time){
                $user->setIsOnline(false);
                $user->setItem(null);
                $dm->flush();
            }else{
              $key = $user->getId();
              $onlineUsers[$key]['id'] = $user->getId();
              $onlineUsers[$key]['username'] = $user->getUsername();
              $onlineUsers[$key]['is_online'] = $user->getIsOnline();
              $onlineUsers[$key]['last_updated'] = $user->getLastUpdated()->getTimestamp();
              $onlineUsers[$key]['position_x'] = $user->getPositionX();
              $onlineUsers[$key]['position_y'] = $user->getPositionY();
              $onlineUsers[$key]['position_z'] = $user->getPositionZ();
              $onlineUsers[$key]['item'] = array();

              $item = $user->getItem();
              if(!empty($item)){
                  $onlineUsers[$key]['item']["id"] = $item->getId();
                  $onlineUsers[$key]['item']["name"] = $item->getName();
                  $onlineUsers[$key]['item']['position_x'] = $item->getPositionX();
                  $onlineUsers[$key]['item']['position_y'] = $item->getPositionY();
                  $onlineUsers[$key]['item']['position_z'] = $item->getPositionZ();
              }
            }
        }

        if($onlineUsers){
        	unset($onlineUsers[$data['id']]);
        }

        $info = array('userData'=>$data, 'items'=> $items, 'onlineUsers'=> $onlineUsers);

        $view = new ViewModel($info);
        $view->setTemplate('application/index/index');
        
        return $view;
    }
}
