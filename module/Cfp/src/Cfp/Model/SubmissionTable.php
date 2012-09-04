<?php

namespace Cfp\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class SubmissionTable extends AbstractTableGateway
{
	protected $table     = 'submission';
	protected $tableRel  = 'submission_user';
	protected $tableConf = 'conference';
	
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
	
    public function getSubmission($submission_id)
    {
        $submission_id = (int) $submission_id;
        $rowset = $this->select(array('submission_id' => $submission_id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $submission_id");
        }
        return $row;
    }
	
	public function getSubmissionsByUser($user_id)
	{
		$adapter = $this->adapter;
		$sql = new Sql($adapter);
		$select = $sql->select();
        $user_id = (int) $user_id;
		
		$select->from(array('t' => $this->table));

		$select->join(array('tr' => $this->tableRel), 't.submission_id = tr.submission_id');
		$select->join(array('tc' => $this->tableConf), 't.conference_id = tc.conference_id', array('short_name'));

		$select->where(array('tr.user_id' => $user_id));

		$select->order('date_sent DESC');
					
		$selectString = $sql->getSqlStringForSqlObject($select);

		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

        return $results->count() ? $results->toArray() : array();

	}

	public function saveSubmission(Submission $submission)
	{
		$extract = array(
			'submission_id', 'conference_id', 'track_id', 'title', 'abstract',
			'duration', 'date_sent', 'accepted',
			// @TODO remove this
			'minicurriculo',
		);
		
		$data = array();
		
		foreach ($extract as $ext)
			$data[$ext] = $submission->$ext;

		$id = (int) $submission->submission_id;
		
		// Includes today's date
		if (!$data['date_sent'])
			$data['date_sent'] = date('Y-m-d');

		if ($id == 0) {
			$this->insert($data);
			$id = $this->getLastInsertValue();
		} else {
			if ($this->getSubmission($id)) {
				$this->update($data, array('submission_id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
		
		// Returns the last insert ID :)
		return $id;
	}
	
	public function deleteSubmission($id)
	{
		$this->delete(array('submission_id' => $id));
	}
}