<?php

namespace Admin\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class ConferenceTable extends AbstractTableGateway
{
    protected $table = 'conference';
	protected $table_schedule = 'conference_schedule';
	protected $table_attendee = 'conference_attendee';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Conference());
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

	/**
	 * Used to get full data of conferences, including date and stuff
	 */
    public function fetchFullData($id = null)
    {
		$adapter = $this->adapter;
        $sql = new Sql($adapter);
		$select = $sql->select();

		$select->from(array('t' => $this->table));

		$select->join(array('ts' => $this->table_schedule), 't.conference_id = ts.conference_id');

		$where = array('active' => 1);
		if ($id)
			$where['t.conference_id'] = $id;
		$select->where($where);

		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
	
		return $results;
    }

	/**
	 * Used to get full data of conferences with open calls, including date and stuff
	 */
    public function fetchOpenCalls()
    {
		$adapter = $this->adapter;
        $sql = new Sql($adapter);
		$select = $sql->select();

		$select->from(array('t' => $this->table));

		$select->join(array('ts' => $this->table_schedule), 't.conference_id = ts.conference_id');

		$select->where(function (\Zend\Db\Sql\Where $where) { $where->greaterThanOrEqualTo('ts.cfp_closed', date('Y-m-d H:i:s'));});

		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
	
		return $results;
    }

	/**
	 * Used to get full data of upcoming conferences, including date and stuff
	 */
    public function fetchUpcomingConferences()
    {
		$adapter = $this->adapter;
        $sql = new Sql($adapter);
		$select = $sql->select();

		$select->from(array('t' => $this->table));

		$select->join(array('ts' => $this->table_schedule), 't.conference_id = ts.conference_id');

		$select->where(function (\Zend\Db\Sql\Where $where) { $where->greaterThanOrEqualTo('ts.last_day', date('Y-m-d H:i:s'));});

		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
	
		return $results;
    }

	/**
	 * Check if call for this conference is open
	 */
	public function callIsOpen($conference_id)
	{
		$adapter = $this->adapter;
        $sql = new Sql($adapter);
		$select = $sql->select();

		$select->from(array('t' => $this->table));

		$select->join(array('ts' => $this->table_schedule), 't.conference_id = ts.conference_id');

		$select->where(function (\Zend\Db\Sql\Where $where) { $where->greaterThanOrEqualTo('ts.cfp_closed', date('Y-m-d H:i:s'));});
		$select->where(array('t.conference_id' => $conference_id));
		
		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
		
		return (bool) $results->count();
	}
	/**
	 * status	registered, accreditated, null (for everything)
	 */
    public function getConferencesByAttendee($id, $status = null)
    {
		$adapter = $this->adapter;
        $sql = new Sql($adapter);
		$select = $sql->select();

		$select->from(array('t' => $this->table));

		$select->join(array('ta' => $this->table_attendee), 't.conference_id = ta.conference_id');

		$where = array('ta.user_id' => $id);
		
		switch ($status)
		{
			case 'registered':
				$where['accreditation'] = null;
				break;
				
			case 'accreditated':
				$where[] = new \Zend\Db\Sql\Predicate\IsNotNull('accreditation');
		}
		
		$select->where($where);

		$select->order('register DESC, accreditation DESC');

		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
	
		return $results;
    }

    public function getConference($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('conference_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

	public function saveConference(Conference $conference)
	{
		$extract = array(
			'conference_id', 'user_id', 'name', 'short_name', 'description', 'registration_fee', 'location',
			'address', 'city', 'state', 'country', 'gmt', 'show_running', 'active'
		);
		
		$data = array();
		
		foreach ($extract as $ext)
			$data[$ext] = $conference->$ext;

		$id = (int) $conference->conference_id;

		if ($id == 0) {
			$this->insert($data);
		} else {
			if ($this->getConference($id)) {
				$this->update($data, array('conference_id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
	
	public function deleteConference($id)
	{
		$this->delete(array('conference_id' => $id));
	}
}