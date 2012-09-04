<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Cfp\Model\SubmissionTable;

class MySubmissionsController extends AbstractActionController
{
	
	private $submissionTable;
	private $zfcUser;
	
    public function indexAction()
    {
		$subsTable = $this->getSubmissionTable();
		$subs = $subsTable->getSubmissionsByUser($this->zfcUserAuthentication()->getIdentity()->getId());

        return array('subs' => $subs);
    }
	
	protected function getSubmissionTable()
	{
		if (!$this->submissionTable)
			$this->submissionTable = $this->getServiceLocator()->get('Cfp\Model\SubmissionTable');
		return $this->submissionTable;
	}

	protected function zfcUserAuthentication()
	{
		if (!$this->zfcUser)
			$this->zfcUser = $this->getServiceLocator()->get('zfcuser_auth_service');
		return $this->zfcUser;
	}
}