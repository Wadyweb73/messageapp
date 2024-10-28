<?php

class DBConnection {
	private static $_instance = null;
	private $_pdo;
	private $_query;
	private $_error;
	private $_results;
	private $_count = 0;

    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/hostname') . ';dbname=' . Config::get('mysql/database'), Config::get('mysql/username'), Config::get('mysql/password'));
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

	public static function getInstance() {
		if (self::$_instance == null) {
			self::$_instance = new DBConnection();
		}

		return self::$_instance;
	}

	public function query($sql, $params=array())	{
		$this->_error = false;

		if ($this->_query=$this->_pdo->prepare($sql)) {
			if (count($params)) {
				$pos = 1;

				foreach ($params as $param) {
					$this->_query->bindValue($pos, $param);
					++$pos;
				}
			}

			try {
				if ($this->_query->execute()) {
					$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
					$this->_count   = $this->_query->rowCount(); 
				}
				else {
					$this->_error = true;
				}
			}
			catch(Exception $e) {	
                echo $e->getMessage();
                die();
				//die($this->verifyErrorCode($e));
			}
		}	

		return $this;
	}

	// verifica o tipo de errro originado na BD para 
	// exibir uma mensagem ao usuario a informa-lo sobre o erro
	private function verifyErrorCode($code) {
		switch ($code->getCode()) {
			case 23000:
				echo "<h1>Esse registo ja existe na Base de Dados</h1>";
			break;

			case 1:

			break;

			case 1:

			break;
			
			case 1:

			break;
		}
	}

	private function action($action, $table, $where=array()) {
		if (count($where) === 3) {
			$operators = array('>', '<', '>=', '<=', '=');

			$fields   = $where[0];
			$operator = $where[1];
			$value    = $where[2];

			if (in_array($operator, $operators)) {
				$req = "{$action} FROM {$table} WHERE {$fields} {$operator} ?";

				if (!$this->query($req, array($value))->error()) {
					return $this;	
				}
			}
		}

		return false;
	}

	public function insert($table, $fields) {
		if (count($fields)) {
			$keys    = array_keys($fields);
			$values  = null;
			$counter = 1;

			foreach ($fields as $field) {
				$values .= '?';		

				if ($counter < count($fields)) {
					$values .= ', ';
					++$counter;
				}
			}

			$req = "INSERT INTO {$table} (`" . implode("`, `", $keys) . "`) VALUES ({$values})";

			if (!$this->query($req, $fields)->error()) {
				return true;
			}
		}

		return false;
	}
	 
	public function get($table, $where) {
		return $this->action("SELECT *", $table, $where);
	}

	public function update($table, $id, $fields) {
		$set     = "";
		$counter = 1;

		foreach ($fields as $field_name => $field_value) {
			$set .= "{$field_name} = ?";

			if ($counter < count($fields)) {
				$set .= ", ";
			}

			++$counter;
		}

		$req = "UPDATE {$table} SET {$set} WHERE id = {$id}";

		if (!$this->query($req, $fields)->error()) {
			return true;
		}

		return false;
	}
	
	public function update2($table, $fields, $where) {
        $set     = "";
        $counter = 1;

        // Construir a parte SET da query
        foreach ($fields as $field_name => $field_value) {
            $set .= "{$field_name} = ?";
            if ($counter < count($fields)) {
                $set .= ", ";
            }
            ++$counter;
        }

        // Construir a parte WHERE da query
        $where_clause = "";
        if (count($where) === 3) {
            // Se houver apenas uma condição
            $where_clause = "WHERE {$where[0]} {$where[1]} ?";
        } elseif (count($where) > 3) {
            // Se houver várias condições
            $where_clause = "WHERE ";
            $counter = 1;
            foreach ($where as $condition) {
                $where_clause .= "{$condition[0]} {$condition[1]} ?";
                if ($counter < count($where)) {
                    $where_clause .= " AND ";
                }
                ++$counter;
            }
        }

        // Construir a query final
        $req = "UPDATE {$table} SET {$set} {$where_clause}";

        // Junta todos os parâmetros de update e de where
        $params = array_merge(array_values($fields), array_map(function($condition) {
            return $condition[2]; // O valor da condição WHERE
        }, $where));
        
        echo $req;
        die();

        // Executa a query
        if (!$this->query($req, $params)->error()) {
            return true;
        }

        return false;
    }


	public function delete($table, $where) {
		return $this->action("DELETE", $table, $where);
	}

	public function error() {
		return $this->_error;
	}

	public function results() {
		return $this->_results;
	}

	public function getFirst() {
		return $this->_results[0];
	}

	public function count() {
		return $this->_count;
	}
}

?>
