<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
    }

    public function indexAction()
    {
    	$tourneyTable = new Model_DbTable_Tourney();
    	$sql = $tourneyTable->select();
    	$stmt = $sql->query();
    	$result = $stmt->fetchAll();
    	$tourneyList = array();
    	foreach ($result as $row) {
    		$tourneyList[] = Model_Type_Abstract::factory($row['id']);
    	}
    	$this->view->tourneyList = $tourneyList;
    }
}
