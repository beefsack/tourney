<?php

class TestController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    	$this->view->headTitle("YA MUM");
    	$dataObject1 = new Model_TourneyData("egg:yolk:banana;banana:split;");
    	$dataObject = new Model_TourneyData($dataObject1);
    	$dataObject['fart'] = 'bacon';
    	$dataObject['smeg'] = 'chortle';
    	$dataObject['boobs'] = 'hat';
    	$this->view->dataObject = $dataObject;
    	$egg = new Model_DbTable_Game();
    	$insertdata['name'] = 'egg';
    	$insertdata['description'] = 'this game is an egg';
    	$insertdata['scoringtype'] = 'highestscore';
    	$egg->insert($insertdata);
    }
}
