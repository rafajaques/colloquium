<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Admin\Form\ConferenceForm;

use Admin\Model\Conference;
use Admin\Model\ConferenceTable;

class ConferencesController extends AbstractActionController
{
	
	protected $conferenceTable;
	protected $submissionTable;
	protected $conferenceAttendeeTable;
	
	public function indexAction()
	{
		return array(
			'conferences' => $this->getConferencesTable()->fetchAll(),
		);
	}

	public function addAction()
	{
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		$form = new ConferenceForm(null, $user_id);
		
		$request = $this->getRequest();

		if ($request->isPost()) {
			$conference = new Conference();
			$form->setInputFilter($conference->getInputFilter());
			$form->setData($request->getPost());

			if ($form->isValid()) {
				$conference->exchangeArray($form->getData());
				$this->getConferencesTable()->saveConference($conference);

				// Redirect to list
				return $this->redirect()->toRoute('admin/conferences');
			}
		}
		
		return(array('form' => $form));
	}
	
	public function submissionsAction()
	{
		$id = (int) $this->params()->fromRoute('id');
		
		$confName = $this->getConferencesTable()->getConference($id);
		
		$subsTable = $this->getSubmissionTable();
		$subs = $subsTable->getSubmissionsByConference($id);
		
		return array('submissions' => $subs, 'conference' => $confName);
	}
	
	public function attendeesAction()
	{
		$id = (int) $this->params()->fromRoute('id');
		
		$confName = $this->getConferencesTable()->getConference($id);
		
		//$subsTable = $this->getSubmissionTable();
		//$subs = $subsTable->getSubmissionsByConference($id);
		
		$attendees = $this->getConferenceAtendeeTable()->getAttendeesByConference($id);

		return array('conference' => $confName, 'attendees' => $attendees);
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
	
	protected function getSubmissionTable()
	{
		if (!$this->submissionTable)
		{
			$sm = $this->getServiceLocator();
			$this->submissionTable = $sm->get('Cfp\Model\SubmissionTable');
		}
		return $this->submissionTable;
	}
	
	protected function getConferenceAtendeeTable()
	{
		if (!$this->conferenceAttendeeTable)
		{
			$sm = $this->getServiceLocator();
			$this->conferenceAttendeeTable = $sm->get('Admin\Model\ConferenceAttendeeTable');
		}
		return $this->conferenceAttendeeTable;
	}
}