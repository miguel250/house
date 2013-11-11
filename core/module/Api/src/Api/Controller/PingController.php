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

class PingController extends ApiController
{
    public function getList()
    {
       $params = $this->params()->fromQuery();

       $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
       $users = $dm->getRepository('Application\Document\User')->findByisOnline(true);
       $data = array('users'=> array(), 'items'=>array());
       if(isset($params['disconnected']) && $params['disconnected']){
            foreach ($users as $key => $user) {
                $now = new \DateTime("-5 seconds");
                $time = $now > $user->getLastUpdated();
                if($time){
                    $user->setIsOnline(false);
                    $user->setItem(null);
                    $dm->flush();
                }else{
                  $key = $user->getId();
                  $data['users'][$key]['id'] = $user->getId();
                  $data['users'][$key]['username'] = $user->getUsername();
                  $data['users'][$key]['is_online'] = $user->getIsOnline();
                  $data['users'][$key]['last_updated'] = $user->getLastUpdated()->getTimestamp();
                  $data['users'][$key]['position_x'] = $user->getPositionX();
                  $data['users'][$key]['position_y'] = $user->getPositionY();
                  $data['users'][$key]['position_z'] = $user->getPositionZ();
                  $data['users'][$key]['item'] = array();

                  $item = $user->getItem();
                  if(!empty($item)){
                      $data['users'][$key]['item']["id"] = $item->getId();
                      $data['users'][$key]['item']["name"] = $item->getName();
                      $data['users'][$key]['item']['position_x'] = $item->getPositionX();
                      $data['users'][$key]['item']['position_y'] = $item->getPositionY();
                      $data['users'][$key]['item']['position_z'] = $item->getPositionZ();
                  }
                }
            }

            $items_obj = $dm->getRepository('Application\Document\Item')->findAll();

            if(count($items_obj)!= 0){
                foreach ($items_obj as $key => $item) {
                    $key = $item->getId();
                    $items[$key]["id"] = $item->getId();
                    $items[$key]["name"] = $item->getName();
                    $items[$key]['position_x'] = $item->getPositionX();
                    $items[$key]['position_y'] = $item->getPositionY();
                    $items[$key]['position_z'] = $item->getPositionZ();
                }
                $data['items'] = $items;
            }

            
       }

       if(!empty($data['users'])){
          $container = new Container('house');
          $id = $container->userId;
          unset($data['users'][$id]);
       }

       return new JsonModel($data);
    }
}