<?php

namespace Esmt\Pharmaliv; 

class Action {
	private $_request;
	private $_response;

	function __construct ( $req, $resp ) {
		$this->_request = $req;
		$this->_response = $resp;
	}

	function request () {
		return $this->_request;
	}

	function response () {
		return $this->_response;
	}
}