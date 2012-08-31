<?php

namespace Admin\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Sql;

class UserRoleLinkerTable extends AbstractTableGateway
{
    protected $table = 'user_role_linker';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new UserRoleLinker());
        $this->initialize();
    }

    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;
    }

    public function getUserRoleLinker($id)
    {
        $id  = (int) $id;
        $rowset = $this->select(array('user_id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

	public function saveLink($data)
	{
		$this->deleteUserRoleLinker($data['user_id']);
		$this->insert($data);
	}

	public function saveUserRoleLinker(UserRoleLinker $linker)
	{
		$extract = array(
			'user_id', 'role_id',
		);
		
		$data = array();
		
		foreach ($extract as $ext)
			$data[$ext] = $conference->$ext;

		$id = (int) $conference->conference_id;

		if ($id == 0) {
			$this->insert($data);
		} else {
			if ($this->getUserRoleLinker($id)) {
				$this->update($data, array('user_id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
	
	public function deleteUserRoleLinker($id)
	{
		$this->delete(array('user_id' => $id));
	}
}