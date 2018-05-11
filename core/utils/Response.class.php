<?php

class Response {
	private $_templateDirs;

	function __construct () {
		$this->_templateDirs = array ( './templates' );
	}

	function addTemplateDir ( $templateDir ) {
		$this->_templateDirs [] = $templateDir;
	}

	function render ( $template, $data = null ) {
		require_once './vendor/autoload.php';

		if ($data == null){
			$data = array();
		}

		$loader = new Twig_Loader_Filesystem( $this->_templateDirs );
		$twig = new Twig_Environment( $loader );

		$data['rootDir'] = dirname($_SERVER['PHP_SELF']);
		
		echo $twig->render( $template, $data );
	}

	function redirect ( $url ){
        $root = dirname( $_SERVER['PHP_SELF'] );
        $url = $root . $url;
        header( "location:".$url );
    }

    function toJson ($data){
	    echo json_encode($data);
    }
}