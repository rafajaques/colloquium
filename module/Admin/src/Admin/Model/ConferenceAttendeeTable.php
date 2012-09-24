<?php

namespace Admin\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class ConferenceAttendeeTable extends AbstractTableGateway
{
	protected $table = 'conference_attendee';
	protected $table_users = 'user';

	public function __construct(Adapter $adapter)
	{
		$this->adapter = $adapter;
		$this->initialize();
	}

	public function fetchAll()
	{
		$resultSet = $this->select();
		return $resultSet;
	}
	
	public function getAttendeesByConference($conference_id)
	{
		$adapter = $this->adapter;
        $sql = new Sql($adapter);
		$select = $sql->select();

		$select->from(array('t' => $this->table));

		$select->join(array('tu' => $this->table_users), 't.user_id = tu.user_id');

		$select->where(array('t.conference_id' => $conference_id));

		$select->order('register DESC, accreditation DESC');

		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

		return $results;
	}

	public function userAttendingToConference($user_id, $conference_id)
	{
		$result = $this->select(array('user_id' => $user_id, 'conference_id' => $conference_id));
		
		if ($result->count()) {
			$output = $result->toArray();
			return $output[0];
		}
		
		return false;
	}

	public function saveAttendee($data)
	{
		$extract = array(
			'conference_id', 'user_id', 'disclose_email'
		);

		$attendee = array();

		foreach ($extract as $ext) {
			if (!isset($data[$ext]))
				throw new \Exception('Incomplete data set');
			$attendee[$ext] = $data[$ext];
		}
		
		/**
		 * @TODO consider GMT when getting this date
		 */
		$attendee['register'] = date('Y-m-d H:i:s');

		/**
		 * Users shouldn't be able to attend a conference two times
		 */
		if ($this->userAttendingToConference($attendee['user_id'], $attendee['conference_id']))
			throw new \Exception('Users shouldn\'t be able to attend a conference two times');

		$this->insert($attendee);
	}

	public function deleteConference($id)
	{
		$this->delete(array('conference_id' => $id));
	}
}