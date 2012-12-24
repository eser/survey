<?php

	require('../scabbia/framework.php');

	// framework::$endpoints[] = 'http://localhost/survey';
	framework::$development = 1;
	framework::load(false);
	
	database::$errorHandling = database::ERROR_EXCEPTION;

	framework::run();

?>