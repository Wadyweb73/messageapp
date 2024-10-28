<?php

class Validate {
	private $_passed;
	private $_errors;
	private $_db    ;

	public function __construct() {
		$this->_passed = false;
		$this->_errors = array();
		$this->_db     = DBConnection::getInstance();
	}

	public function check($type, $fields=array()) {
		foreach ($fields as $field => $rules) {
			$field_value = trim($type[$field]);

			foreach($rules as $rule_name => $rule_value) {
				if ($rule_name === 'required' && empty($field_value)) {
					$this->addError("{$field} is required");
				}
				else {
					switch ($rule_name) {
						case 'min':
							if (strlen($field_value) < $rule_value) {
								$this->addError("{$field} must be a minimum of {$rule_value} characters!");
							}	
						break;

						case 'max':
							if (strlen($field_value) > $rule_value) {
								$this->addError("{$field} must be a maximum of {$rule_value} characters");
							}	
						break;

						case 'matches':
							if ($field_value !== $type[$rule_value]) {
								$this->addError("{$field} cannot match {$rule_value}");
							}
						break;

						case 'unique':
							$check = $this->_db->get($rule_value, array($field, '=', $field_value));	

							if ($check->count()) {
								$this->addError("{$field} is already in use!");
							}
						break;

						case 'email':
							if ($rule_value === true) {
								if (!filter_var($field_value, FILTER_VALIDATE_EMAIL)) {
									$this->addError("{$field} must be a valid email address");
								}
							}
						break;
					}
				}
			} 
		}

		if (empty($this->_errors)) {
			$this->_passed = true;
		}

		return $this;
	}

	public function addError($error) {
		$this->_errors[] = $error;
	} 

	public function errors() {
		return $this->_errors;
	}

	public function passed() {
		return $this->_passed;
	}
}

?>
