<?php

namespace yozh\multilingual;

use \lajax\translatemanager\Module as LxModule;
use yozh\base\traits\patterns\DecoratorTrait;

class Module extends \yozh\base\Module
{
	use DecoratorTrait;
	
	const MODULE_ID = 'multilingual';
	
	public $controllerNamespace = 'yozh\\' . self::MODULE_ID . '\controllers';
	
	public function __construct( string $id, ?\yii\base\Module $parent = null, array $config = [] )
	{
		$this->_object = new LxModule( 'translatemanager', $parent, $config );
		
		parent::__construct( $id, $parent, $config );
		
	}
	
}