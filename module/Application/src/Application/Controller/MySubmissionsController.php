<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel,
	Zend\View\Model\JsonModel;
use Cfp\Model\SubmissionUser;

class MySubmissionsController extends AbstractActionController
{
	
	protected $submissionTable;
	protected $userTable;
	protected $submissionUserTable;
	protected $zfcUser;
	
    public function indexAction()
    {
		$subsTable = $this->getSubmissionTable();
		$subs = $subsTable->getSubmissionsByUser($this->zfcAuth()->getIdentity()->getId());

        return array('subs' => $subs);
    }
	
	public function jsSpeakersListAction()
	{
		$params = $this->params()->fromRoute();

		if (!$submission_id = $params['id'])
			die;
			
		$users = $this->getSubmissionUserTable()->getUsersBySubmission($submission_id);

		return new JsonModel($users);
	}
	
	/**
	 * @TODO this snippet is awful!
	 */
	public function jsMailCheckAction()
	{
		if (!$email = $this->params()->fromRoute('email', 0))
			die;
		
		echo $this->getUserTable()->getIdByEmail($email);
		die;
	}
	
	/**
	 * @TODO this is SO ugly!
	 */
	public function jsSpeakersAddAction()
	{
		$request = $this->getRequest();
		if (!$request->isPost())
			die;
		$post = $request->getPost();
		$submission_id = $post['submission_id'];
		$user_id = $post['user_id'];
		
		// Creates the link and set as 'pending'
		$submissionUser = new SubmissionUser();
		$submissionUser->exchangeArray(array(
			'submission_id'	=> $submission_id,
			'user_id'		=> $user_id,
			'status'		=> 'pending',
			'main'			=> 0,
		));
				
		try
		{
			$this->getSubmissionUserTable()->saveSubmissionUser($submissionUser);	
		} catch (\Exception $e)
		{
			echo 0;
			die;
		}
		echo 1;
		die;
	}
	
	protected function getSubmissionTable()
	{
		if (!$this->submissionTable)
			$this->submissionTable = $this->getServiceLocator()->get('Cfp\Model\SubmissionTable');
		return $this->submissionTable;
	}
	
	protected function getUserTable()
	{
		if (!$this->userTable)
			$this->userTable = $this->getServiceLocator()->get('Admin\Model\UserTable');
		return $this->userTable;
	}

	protected function zfcAuth()
	{
		if (!$this->zfcUser)
			$this->zfcUser = $this->getServiceLocator()->get('zfcuser_auth_service');
		return $this->zfcUser;
	}
	
	protected function getSubmissionUserTable()
	{
		if (!$this->submissionUserTable)
			$this->submissionUserTable = $this->getServiceLocator()->get('Cfp\Model\SubmissionUserTable');
		return $this->submissionUserTable;
	}
	
}