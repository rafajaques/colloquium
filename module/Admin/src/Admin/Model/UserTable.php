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
        $this->initialize();
    }

	public function getEmailByCredential($credential)
	{
		$adapter = $this->adapter;
		$sql = new \Zend\Db\Sql\Sql($adapter);
		$select = $sql->select();
		$select->from('user');
		$select->where(sprintf('username = "%s" OR email = "%s"', $credential, $credential));
		//$select->where(function (\Zend\Db\Sql\Where $where) { $where->greaterThanOrEqualTo('ts.cfp_closed', date('Y-m-d H:i:s'));});

		$selectString = $sql->getSqlStringForSqlObject($select);
		$results = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
		if ($results->count() != 1)
			return false;
			
		return $results->current()->email;
	}
	
	public function setRecoveryHash($email)
	{
		$hash = sha1(md5(mktime()*mt_rand(1,500)));
		$this->update(array('recovery_hash' => $hash), array('email' => $email));
		return $hash;
	}
	
	public function hashExists($hash)
	{
		$select = $this->select(array('recovery_hash' => $hash));
			return (bool) $select->count() == 1;
	}
	
	public function hardChangePassword($password, $hash) {
		$this->update(array('password' => $password, 'recovery_hash' => ''), array('recovery_hash' => $hash));
	}

    /*public function fetchAll()
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
	}*/
}