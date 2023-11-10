<?php


namespace MF\Model;

abstract class Model {

	protected $db;

	protected $tableName = null;

	public function __construct(\PDO $db) {
		$this->db = $db;

		$tableName = strtolower(str_replace("App\\Models\\", '', get_called_class()));
		$this->tableName = $tableName;
	}

	public function getTableName()
	{
		return $this->tableName;
	}

	public function buildInsertQuery($data)
	{

		$insertInto = "INSERT INTO " . $this->getTableName() . " (";
		$insertInto .= implode(', ', array_keys($data)) . ") ";
		$insertInto .= "VALUES (";

		$bindData = array();
		foreach ($data as $key => $value) {
			$bindData[$key] = ':' . $key;
		}
		$insertInto .= implode(', ', $bindData) . ")";

		return $insertInto;
	}

	public function save($data)
	{

		$query = $this->buildInsertQuery($data);
		$stmt = $this->db->prepare($query);

		foreach($data as $key => $val) {
			
			$stmt->bindValue(':'.$key, $val);
		}
		$stmt->execute();

	}
}


?>