<?php

namespace Admin\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class UserTable extends AbstractTableGateway
{
    protected $table = 'user';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        //$this->resultSetPrototype = new ResultSet();
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getUser($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
	
	public function getUserByEmail($email)
	{
        $rowset = $this->select(array('email' => $email));

        return (bool) $rowset->count();
	}
	
	public function getIdByEmail($email)
	{
        $rowset = $this->select(array('email' => $email));
		if ($rowset->count())
		{
			$current = $rowset->current();
			return $current['user_id'];
		}
        return 0;
	}
}