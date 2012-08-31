<?php

namespace Cfp\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class SubmissionTable extends AbstractTableGateway
{
	protected $table = 'submission';
	protected $table_user_rel = 'submission_user';
	
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Submission());
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
	
    public function getSubmission($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('submission_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

	public function saveSubmission(Submission $submission)
	{
		$extract = array(
			'submission_id', 'conference_id', 'track_id', 'title', 'abstract',
			'duration', 'accepted',
			// @TODO remove this
			'minicurriculo',
		);
		
		$data = array();
		
		foreach ($extract as $ext)
			$data[$ext] = $submission->$ext;

		$id = (int) $submission->submission_id;

		if ($id == 0) {
			$this->insert($data);
		} else {
			if ($this->getSubmission($id)) {
				$this->update($data, array('submission_id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
	
	public function deleteConference($id)
	{
		$this->delete(array('submission_id' => $id));
	}
}