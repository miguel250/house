<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Application\Document\User;

class UserController extends ApiController
{
    public function create($data)
    {
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        
        $user = new User();
        $user->setUsername($data['username']);

        $dm->persist($user);
        $dm->flush();

        $data['id'] = $user->getId();
        $data['is_online'] = $user->getIsOnline();
        $data['last_updated'] = $user->getLastUpdated()->getTimestamp();
        $data['position_x'] = $user->getPositionX();
        $data['position_y'] = $user->getPositionY();
        $data['position_z'] = $user->getPositionZ();
        $data['items'] = array();

        $container = new Container('house');
        $container->init = 1;
        $container->userId = $data['id'];

        return new JsonModel($data);
    }

    public function get($id, $json=true){
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        
        $data = array();
        
        $user = $dm->getRepository('Application\Document\User')->find($id);
        
        if(!empty($user)){
            $data['id'] = $user->getId();
            $data['username'] = $user->getUsername();
            $data['is_online'] = $user->getIsOnline();
            $data['last_updated'] = $user->getLastUpdated()->getTimestamp();
            $data['position_x'] = $user->getPositionX();
            $data['position_y'] = $user->getPositionY();
            $data['position_z'] = $user->getPositionZ();
            $data['item'] = array();

            $item = $user->getItem();
            if(!empty($item)){
                $data['item']["id"] = $item->getId();
                $data['item']["name"] = $item->getName();
                $data['item']['position_x'] = $item->getPositionX();
                $data['item']['position_y'] = $item->getPositionY();
                $data['item']['position_z'] = $item->getPositionZ();
            }
        }elseif($json){
            $this->response->setStatusCode(404);
            $data['message'] = "Not Found";
        }
        
        if(!$json){
            $data['user'] = $user;
            $data['dm'] = $dm;
            return $data;
        }

        return new JsonModel($data);
    }

    public function patch($id, $data){
        $data_old = $this->get($id, false);
        $user = $data_old['user'];
        $dm = $data_old['dm'];

        if(empty($user)){
            $this->response->setStatusCode(404);
            $response = array();
            $response['message'] = "Not Found";
            return new JsonModel($response);
        }
        
        unset($data_old['user']);
        unset($data_old['dm']);

        if(isset($data['is_online'])){
            $user->setIsOnline($data['is_online']);
            $data_old['is_online'] = $data['is_online'];
        }

        if(isset($data['position_x'])){
            $user->setPositionX($data['position_x']);
            $data_old['position_x'] = $data['position_x'];
        }

        if(isset($data['position_y'])){
            $user->setPositionY($data['position_y']);
            $data_old['position_y'] = $data['position_y'];
        }

        if(isset($data['position_z'])){
            $user->setPositionZ($data['position_z']);
            $data_old['position_z'] = $data['position_z'];
        }

        if(isset($data['item']) && $data['item'] == "null"){
            $user->setItem(null);
            unset($data_old['item']);
        }elseif(isset($data['item']['id'])) {
            $item_id = $data['item']['id'];
            $item = $dm->getRepository('Application\Document\Item')->find($item_id);
            
            if(!empty($item)){
                $user->setItem($item);
                $data_old['item']["id"] = $item->getId();
                $data_old['item']["name"] = $item->getName();
                $data_old['item']['position_x'] = $item->getPositionX();
                $data_old['item']['position_y'] = $item->getPositionY();
                $data_old['item']['position_z'] = $item->getPositionZ();
            }
        }
    
        $user->setLastUpdated();
        $data_old['last_updated'] = $user->getLastUpdated()->getTimestamp();

        $dm->flush();
        
        return new JsonModel($data_old);
    }
}