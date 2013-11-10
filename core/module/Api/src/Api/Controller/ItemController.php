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
use Application\Document\Item;

class ItemController extends ApiController
{
    public function getList()
    {
        $data = array();
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        $items = $item = $dm->getRepository('Application\Document\Item')->findAll();
        if(!empty($items)){
            $count = 0;
            foreach ($items as $key => $item) {
                $data[$count]["id"] = $item->getId();
                $data[$count]["name"] = $item->getName();
                $data[$count]['position_x'] = $item->getPositionX();
                $data[$count]['position_y'] = $item->getPositionY();
                $data[$count]['position_z'] = $item->getPositionZ();
                $count++;
            }
        }elseif($json){
            $this->response->setStatusCode(404);
            $data['message'] = "Not Found";
        }

        return new JsonModel($data);
    }

    public function create($data)
    {
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        
        $item = new Item();
        $item->setName($data['name']);

        if(isset($data['position_x'])){
            $item->setPositionX($data['position_x']);
        }
        if(isset($data['position_y'])){
            $item->setPositionY($data['position_y']);
        }
        if(isset($data['position_z'])){
            $item->setPositionZ($data['position_z']);
        }

        $data['position_x'] = $item->getPositionX();
        $data['position_y'] = $item->getPositionY();
        $data['position_z'] = $item->getPositionZ();

        $dm->persist($item);
        $dm->flush();

        $data['id'] = $item->getId();
        return new JsonModel($data);
    }

    public function get($id, $json=true){
        $dm = $this->getServiceLocator()->get('doctrine.documentmanager.odm_default');
        
        $data = array();
        
        $item = $dm->getRepository('Application\Document\Item')->find($id);

        if(!empty($item)){
            $data["id"] = $item->getId();
            $data["name"] = $item->getName();
            $data['position_x'] = $item->getPositionX();
            $data['position_y'] = $item->getPositionY();
            $data['position_z'] = $item->getPositionZ();
        }elseif($json){
            $this->response->setStatusCode(404);
            $data['message'] = "Not Found";
        }
        
        if(!$json){
            $data['item'] = $item;
            $data['dm'] = $dm;
            return $data;
        }

        return new JsonModel($data);
    }

    public function patch($id, $data){
        $data_old = $this->get($id, false);
        $item = $data_old['item'];
        $dm = $data_old['dm'];

        if(empty($item)){
            $this->response->setStatusCode(404);
            $response = array();
            $response['message'] = "Not Found";
            return new JsonModel($response);
        }
        unset($data_old['item']);
        unset($data_old['dm']);


        if(isset($data['name'])){
            $item->setName($data['name']);
            $data_old['name'] = $data['name'];
        }

        if(isset($data['position_x'])){
            $item->setPositionX($data['position_x']);
            $data_old['position_x'] = $data['position_x'];
        }

        if(isset($data['position_y'])){
            $item->setPositionY($data['position_y']);
            $data_old['position_y'] = $data['position_y'];
        }

        if(isset($data['position_z'])){
            $item->setPositionZ($data['position_z']);
            $data_old['position_z'] = $data['position_z'];
        }
        $data["id"] = $item->getId();
        $dm->flush();
        return new JsonModel($data_old);
    }
}