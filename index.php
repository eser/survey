<?php

	require('../scabbia/framework.php');

	use Scabbia\framework;
	use Scabbia\database;
	use Scabbia\http;

	// framework::$endpoints[] = 'http://localhost/survey';
	framework::$development = 1;
	framework::load(false);
	
	database::$errorHandling = database::ERROR_EXCEPTION;
	statics::templateBindings();

	framework::run();

?>