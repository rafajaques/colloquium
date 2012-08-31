<?php

namespace Admin\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class TrackTable extends AbstractTableGateway
{
    protected $table = 'conference_track';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Track());
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }
	
    public function getTracksByConference($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('conference_id' => $id));
        if (!$rowset) {
            throw new \Exception("Could not tracks within conference $id");
        }
        return $rowset;
    }

    public function getTrack($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('track_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

	/*public function saveConference(Conference $conference)
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
	}*/
}