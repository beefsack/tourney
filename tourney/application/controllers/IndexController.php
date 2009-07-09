<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    	$this->view->headTitle("tourney - ");
    	$egg = new LadderType();
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
