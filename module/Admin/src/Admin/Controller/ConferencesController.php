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
	
	protected function getConferencesTable()
	{
		if (!$this->conferenceTable)
		{
			$sm = $this->getServiceLocator();
			$this->conferenceTable = $sm->get('Admin\Model\ConferenceTable');
		}
		return $this->conferenceTable;
	}
}