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

class StatusController extends ApiController
{
    public function getList()
    {
       $params = $this->params()->fromQuery();
       $now = new \DateTime("-5 seconds");

       $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
       $users = $dm->getRepository('Application\Document\User')->findByisOnline(true);
       $data = array();
       if(isset($params['disconnected']) && $params['disconnected']){
            foreach ($users as $key => $user) {
                $time = $now > $user->getLastUpdated();
                if($time){
                    $user->setIsOnline(false);
                    $dm->flush();
                }else{
                    $data['id'] = $user->getId();
                    $data['username'] = $user->getUsename();
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
                }
            }
       }

       return new JsonModel($data);
    }
}