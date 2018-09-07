<?php

namespace yozh\multilingual;

class Module extends \yozh\base\Module
{

	const MODULE_ID = 'multilingual';
	
	public $controllerNamespace = 'yozh\\' . self::MODULE_ID . '\controllers';
	
}
