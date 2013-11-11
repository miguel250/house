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
       $data = array();
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
                  $data[$key]['id'] = $user->getId();
                  $data[$key]['username'] = $user->getUsername();
                  $data[$key]['is_online'] = $user->getIsOnline();
                  $data[$key]['last_updated'] = $user->getLastUpdated()->getTimestamp();
                  $data[$key]['position_x'] = $user->getPositionX();
                  $data[$key]['position_y'] = $user->getPositionY();
                  $data[$key]['position_z'] = $user->getPositionZ();
                  $data[$key]['item'] = array();

                  $item = $user->getItem();
                  if(!empty($item)){
                      $data[$key]['item']["id"] = $item->getId();
                      $data[$key]['item']["name"] = $item->getName();
                      $data[$key]['item']['position_x'] = $item->getPositionX();
                      $data[$key]['item']['position_y'] = $item->getPositionY();
                      $data[$key]['item']['position_z'] = $item->getPositionZ();
                  }
                }
            }
       }

       if(!empty($data)){
          $container = new Container('house');
          $id = $container->userId;
          unset($data[$id]);
       }

       return new JsonModel($data);
    }
}