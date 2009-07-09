<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->view->headTitle("tourney - ");
    	$bacon = "Model_MatchupType_Random";
    	$egg = new $bacon();
    	if ($egg instanceof Model_MatchupType_Abstract) {
    		echo $egg->getName();
    		$ham = serialize($egg);
    		echo $ham;
    		$chicken = unserialize($ham);
    		echo $chicken->getName();
    	}
    }

    public function indexAction()
    {
    	$this->view->headTitle("YA MUM");
    }
    
    public function eggAction()
    {
    	$this->view->headTitle("U R AN EGG");
    	$this->view->headTitle("flog");
    	
    }
    
    
    public function baconAction()
    {
    	$this->view->baconvar = "ham";
    }


}
