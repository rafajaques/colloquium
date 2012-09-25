<?php

namespace Cfp\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class SubmissionUserTable extends AbstractTableGateway
{
	protected $table = 'submission_user';
	protected $table_user = 'user';
	
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
        $id = (int) $id;
        $rowset = $this->select(array('submission_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

	public function validateUserSubmission($user_id, $submission_id)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
        $user_id = (int) $user_id;
		$submission_id = (int) $submission_id;
		
		$select->from($this->table);
		$select->where(array('user_id' => $user_id, 'submission_id' => $submission_id, 'status' => 'confirmed'));
		
		$selectString = $sql->getSqlStringForSqlObject($select);

		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

        return (bool) $results->count();
	}

	public function getSubmissionsByUser($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('user_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function getUsersBySubmission($id)
    {
		$id = (int) $id;
		$adapter = $this->adapter;
		$sql = new Sql($adapter);

		$select = $sql->select(array('t' => $this->table));
		$select->columns(array('user_id', 'main', 'status'), 0);
		$select->join(array('tu' => $this->table_user), 't.user_id = tu.user_id', array('display_name', 'email',));
		$select->where(array('submission_id' => $id));
		$selectString = $sql->getSqlStringForSqlObject($select);

		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
		
		return $results->count() ? $results->toArray() : array();
    }

	public function saveSubmissionUser(SubmissionUser $submission)
	{

		$extract = array(
			'submission_id', 'user_id', 'status', 'main',
		);
		
		$data = array();
		
		foreach ($extract as $ext)
			$data[$ext] = $submission->$ext;

        $rowset = $this->select(array('user_id' => $data['user_id'], 'submission_id' => $data['submission_id']));

		if ($rowset->count()) {
			throw new \Exception("User already is a speaker");
		}

		$this->insert($data);
	}
	
	public function deleteSubmissionUser($id)
	{
		$this->delete(array('submission_id' => $id));
	}
}