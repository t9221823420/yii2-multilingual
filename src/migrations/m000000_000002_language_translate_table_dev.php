<?php

/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 07.09.2018
 * Time: 16:20
 */

use yozh\base\components\db\Migration;
use yozh\base\components\db\Schema;
use yozh\multilingual\models\Language;
use yozh\multilingual\models\LanguageSource;
use yozh\base\components\helpers\ArrayHelper;

class m000000_000002_language_translate_table_dev extends Migration
{
	
	//protected static $_table = '{{%tablename}}';
	
	public function __construct( array $config = [] )
	{
		static::$_table = static::$_table ?? \yozh\multilingual\models\LanguageTranslate::getRawTableName();
		
		parent::__construct( $config );
	}
	
	
	public function safeUp( $params = [] )
	{
		if( $this->db->getTableSchema( static::$_table, true ) === null ) {
			throw new \yii\base\Exception( "'{static::$_table}' does not exists. You need to apply lajax/yii2-translate-manager migrations first." );
			
		}
		
		parent::safeUp( [
			'mode' => 1 ? static::ALTER_MODE_UPDATE : static::ALTER_MODE_IGNORE,
		] );
		
	}
	
	public function getColumns( $columns = [] )
	{
		$columns = parent::getColumns( [
			'changed'     => $this->boolean( false ),
		] );
		
		unset( $columns['id'] );
		
		return $columns;
	}
	
	public function getReferences( $references = [] )
	{
		return ArrayHelper::merge( [
			
			[
				'refTable'   => LanguageSource::getRawTableName(),
				'refColumns' => 'id',
				'columns'    => 'id',
				'onDelete'   => self::CONSTRAINTS_ACTION_CASCADE,
			],
		
		], $references );
	}
	
}