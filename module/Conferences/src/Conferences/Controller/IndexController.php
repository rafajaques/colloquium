<?php

namespace Conferences\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Zend\View\Model\JsonModel;

use Helpers\View\Helper\ExtractDate;

class IndexController extends AbstractActionController
{
	
	protected $conferenceTable;
	protected $conferenceAttendeeTable;
	
    public function indexAction()
    {
		return array(
			'conferences'	=> $this->getConferencesTable()->fetchUpcomingConferences(),
			'calls'			=> $this->getConferencesTable()->fetchOpenCalls(),
		);
    }
	
	public function viewAction()
	{
		if (!$conference_id = $this->params()->fromRoute('id', 0))
			return $this->redirect()->toRoute('conferences');
		
		$conference = $this->getConferencesTable()->fetchFullData($conference_id);
		
		if ($att = $this->getConferenceAttendeeTable()->userAttendingToConference($this->getUserId(), $conference_id))
			$registered = $att;
		else
			$registered = false;

		return array(
			'conference'	=> $conference->current(),
			'registered'	=> $registered,
			'conferences'	=> $this->getConferencesTable()->fetchUpcomingConferences(),
			'calls'			=> $this->getConferencesTable()->fetchOpenCalls(),
		);
	}
	
	public function registerAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost() || !$request->getPost('conference_id') || !($data['user_id'] = $this->getUserId()))
			return $this->redirect()->toRoute('conferences');
			
		$data['conference_id']	= (int) $request->getPost('conference_id');
		$data['disclose_email']	= (int) $request->getPost('disclose_email');

		try {
			$this->getConferenceAttendeeTable()->saveAttendee($data);
		}
		catch (\Exception $e)
		{
			return $this->redirect()->toRoute('conferences/inner', array('action' => 'view', 'id' => $data['conference_id'], 'done' => 'double'));	
		}
		
		return $this->redirect()->toRoute('conferences/inner', array('action' => 'view', 'id' => $data['conference_id'], 'done' => 'done'));
	}

	public function jsCalendarAction()
	{
		/**
		 * get translator
		 */
		$sm = $this->getServiceLocator();
		$t = $sm->get('translator');

		/**
		 * return data
		 */
		$conferences = $this->getConferencesTable()->fetchFullData();
		$output = array();
		
		/**
		 * @TODO is there a better way to do that?
		 */
		foreach ($conferences->toArray() as $conf) {
			$output[] = array(
				'title' => $conf['name'],
				'start' => $conf['first_day'],
				'end' => $conf['last_day'],
				'url' => '/conferences/view/' . $conf['conference_id'],
			);
			
			$output[] = array(
				'title' => $t->translate('Call for Papers') . ' ' . $conf['name'],
				'start' => $conf['cfp_opened'],
				'end' => $conf['cfp_closed'],
				'color' => 'green',
				'url' => '/cfp/submit/' . $conf['conference_id'],
			);
			
			$output[] = array(
				'title' => $t->translate('Registration') . ' - ' . $conf['name'],
				'start' => $conf['registration_opened'],
				'end' => $conf['registration_closed'],
				'color' => '#c00000',
				'url' => '/conferences/view/' . $conf['conference_id'],
			);
		}

		return new JsonModel($output);
	}
	
	protected function getUserId()
	{
		if (!$this->zfcUserAuthentication()->getIdentity())
			return false;
		
		return $this->zfcUserAuthentication()->getIdentity()->getId();
	}
	
	protected function getConferencesTable()
	{
		if (!$this->conferenceTable)
		{
			$sm = $this->getServiceLocator();
			$this->conferenceTable = $sm->get('Admin\Model\ConferenceTable');
		}
		return $this->conferenceTable;
	}
	
	protected function getConferenceAttendeeTable()
	{
		if (!$this->conferenceAttendeeTable)
		{
			$sm = $this->getServiceLocator();
			$this->conferenceAttendeeTable = $sm->get('Admin\Model\ConferenceAttendeeTable');
		}
		return $this->conferenceAttendeeTable;
	}
	
}
