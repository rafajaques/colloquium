<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Zend\View\Model\JsonModel;

class MyConferencesController extends AbstractActionController
{
	protected $conferenceTable;
	
    public function indexAction()
    {
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		$confTbl = $this->getConferenceTable();
		
		$registered		= $confTbl->getConferencesByAttendee($user_id, 'registered');
		$accreditated	= $confTbl->getConferencesByAttendee($user_id, 'accreditated');
		
        return array(
        	'registered'	=> $registered->count() ? $registered : false,
			'accreditated'	=> $accreditated->count() ? $accreditated : false,
        );
    }
	
	protected function getConferenceTable()
	{
		if (!$this->conferenceTable)
			$this->conferenceTable = $this->getServiceLocator()->get('Admin\Model\ConferenceTable');
		return $this->conferenceTable;
	}
	
}