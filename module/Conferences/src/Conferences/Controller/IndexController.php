<?php

namespace Conferences\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Zend\View\Model\JsonModel;

use Helpers\View\Helper\ExtractDate;

class IndexController extends AbstractActionController
{
	
	protected $conferenceTable;
	
    public function indexAction()
    {
		return array(
			'conferences' => $this->getConferencesTable()->fetchFullData(),
		);
    }
	
	public function viewAction()
	{
		if (!$conference_id = $this->params()->fromRoute('id', 0))
			return $this->redirect()->toRoute('conferences');
		
		try
		{
			$conference = $this->getConferencesTable()->getConference($conference_id);
		} catch (\Exception $e)
		{
			return $this->redirect()->toRoute('conferences');
		}
		
		return array(
			'conference'	=> $conference,
			'conferences'	=> $this->getConferencesTable()->fetchFullData(),
		);
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
			);
			
			$output[] = array(
				'title' => $t->translate('Call for Papers') . ' ' . $conf['name'],
				'start' => $conf['cfp_opened'],
				'end' => $conf['cfp_closed'],
				'color' => 'green',
			);
			
			$output[] = array(
				'title' => $t->translate('Registration') . ' - ' . $conf['name'],
				'start' => $conf['registration_opened'],
				'end' => $conf['registration_closed'],
				'color' => '#c00000',
			);
		}

		return new JsonModel($output);
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
