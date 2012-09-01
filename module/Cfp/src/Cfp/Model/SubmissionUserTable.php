<?php

namespace Cfp\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class SubmissionUserTable extends AbstractTableGateway
{
	protected $table = 'submission_user';
	
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new SubmissionUser());
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

	public function getSubmissionsByUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('user_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

	public function saveSubmissionUser(SubmissionUser $submission)
	{
		$extract = array(
			'submission_id', 'user_id', 'main',
		);
		
		$data = array();
		
		foreach ($extract as $ext)
			$data[$ext] = $submission->$ext;

		$this->insert($data);
	}
	
	public function deleteSubmissionUser($id)
	{
		$this->delete(array('submission_id' => $id));
	}
}