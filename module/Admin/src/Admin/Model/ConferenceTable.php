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
    public function fetchFullData()
    {
		$adapter = $this->adapter;
        $sql = new Sql($adapter);
		$select = $sql->select();
		$select->from($this->table);
		$select->join($this->table_schedule,  sprintf('%s.conference_id = %s.conference_id', $this->table, $this->table_schedule));
		$select->where(array('active' => 1, /*'date' => '> NOW()'*/));
		
		/*$selectString = 'SELECT
							*,
							DATE_FORMAT(first_day, "%d/%m") first_day_format, 
							DATE_FORMAT(last_day, "%d/%m") last_day_format,
							DATE_FORMAT(cfp_opened, "%d/%m") cfp_opened_format,
							DATE_FORMAT(cfp_closed, "%d/%m") cfp_closed_format,
							DATE_FORMAT(first_day, "%d/%m") first_day_js, 
							DATE_FORMAT(last_day, "%Y, %m, %d") last_day_js,
							DATE_FORMAT(cfp_opened, "%Y, %m, %d") cfp_opened_js,
							DATE_FORMAT(cfp_closed, "%Y, %m, %d") cfp_closed_js
							FROM ' . $this->table . ' JOIN ' . $this->table_schedule . '
							USING(conference_id) WHERE active = 1';*/
		
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
			'conference_id', 'user_id', 'name', 'short_name', 'description', 'location',
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