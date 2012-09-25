<?php

namespace Cfp\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Cfp\Form\SubmissionForm;
use Admin\Model\TrackTable;
use Cfp\Model\Submission;
use Cfp\Model\SubmissionUser;

class IndexController extends AbstractActionController
{
	protected $conferenceTable;
	protected $trackTable;
	protected $submissionTable;
	protected $submissionUserTable;
		
    public function indexAction()
    {
		return array(
			'conferences' => $this->getConferenceTable()->fetchFullData(),
		);
    }
	
	public function submitAction()
	{
		// Check if exists Conference ID
		if (!$conference_id = $this->params()->fromRoute('id', 0)) {
			return $this->redirect()->toRoute('cfp');
		}
		
		// Get User ID and Submission ID
		$user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
		$submission_id = $this->params()->fromRoute('edit', 0);
		
		if ($submission_id && !$this->getSubmissionUserTable()->validateUserSubmission($user_id, $submission_id))
		{
			return $this->redirect()->toRoute('my-submissions');
		}
		
		$tracks_form = array();
		$tracks = $this->getTrackTable()->getTracksByConference((int) $conference_id);

		foreach ($tracks as $t)
			$tracks_form[$t->track_id] = $t->name;

		$form = new SubmissionForm(null, $tracks_form);
		
		// Is it edit?
		if ($submission_id) {
			$edit_subs = $this->getSubmissionTable()->getSubmission($submission_id);
			$form->bind($edit_subs);
		}
		
		// Paper submission
		$request = $this->getRequest();

		if ($request->isPost()) {
			// Insert or edit?
			if (!$submission_id) {
				$submission = new Submission();
				$form->setInputFilter($submission->getInputFilter());
				$form->setData($request->getPost());

				if ($form->isValid()) {
					$formData = $form->getData();
					$formData['conference_id'] = $conference_id;	

					$submission->exchangeArray($formData);

					$submission_id = $this->getSubmissionTable()->saveSubmission($submission);	

					$submissionUser = new SubmissionUser();
					$submissionUser->exchangeArray(array(
						'submission_id'	=> $submission_id,
						'user_id'		=> $user_id,
						'status'		=> 'confirmed',
						'main'			=> 1,
					));
				
					$this->getSubmissionUserTable()->saveSubmissionUser($submissionUser);
				
					return $this->redirect()->toRoute('cfp/ok');
				}
			} else {
				$form->setData($request->getPost());
				
				// This is a sad workaround :(
				$date_sent = $edit_subs->date_sent;
				$accepted = $edit_subs->accepted;
				if ($form->isValid()) {
					/**
					 * @TODO why this data set doesn't hold the values?
					 */
					$edit_subs->submission_id = $submission_id;
					$edit_subs->conference_id = $conference_id;
					$edit_subs->date_sent = $date_sent;
					$edit_subs->accepted = $accepted;
					$this->getSubmissionTable()->saveSubmission($edit_subs);
				}
				return $this->redirect()->toRoute('my-submissions');
			}
		}

		return array(
			'form'          => $form,
			'conference_id' => $conference_id,
			'edit'          => (bool) $submission_id,
			'submission_id' => $submission_id,
		);
	}
	
	public function okAction()
	{
		return array();
	}
	
	protected function getConferenceTable()
	{
		if (!$this->conferenceTable)
			$this->conferenceTable = $this->getServiceLocator()->get('Admin\Model\ConferenceTable');
		return $this->conferenceTable;
	}
	
	protected function getTrackTable()
	{
		if (!$this->trackTable)
			$this->trackTable = $this->getServiceLocator()->get('Admin\Model\TrackTable');
		return $this->trackTable;
	}
	
	protected function getSubmissionTable()
	{
		if (!$this->submissionTable)
			$this->submissionTable = $this->getServiceLocator()->get('Cfp\Model\SubmissionTable');
		return $this->submissionTable;
	}
	
	protected function getSubmissionUserTable()
	{
		if (!$this->submissionUserTable)
			$this->submissionUserTable = $this->getServiceLocator()->get('Cfp\Model\SubmissionUserTable');
		return $this->submissionUserTable;
	}
}
