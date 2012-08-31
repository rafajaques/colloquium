<?php

namespace Application\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class UserTable extends AbstractTableGateway
{
    protected $table = 'user';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new User());
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

	public function saveUser(User $user)
	{
		$data = array(
			'username'			=> $user->username,
			'password'			=> $user->password,
			'name'				=> $user->name,
			'email'				=> $user->email,
			'validation_key'	=> $user->validation_key,
			'valid'				=> $user->valid,
			);

		$id = (int) $user->id;

		if (!$data['validation_key'])
			$data['validation_key'] = $this->genValidationKey();

		if ($id == 0) {
			$this->insert($data);
		} else {
			if ($this->getUser($id)) {
				$this->update($data, array('id' => $id));
			} else {
				throw new \Exception('Form id does not exist');
			}
		}
	}
	
	public function deleteUser($id)
	{
		$this->delete(array('id' => $id));
	}

	private function genValidationKey()
	{
		return sha1(md5(uniqid(mt_rand())));
	}
}