<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 07.09.2018
 * Time: 11:59
 */

namespace yozh\multilingual\models;

use lajax\translatemanager\services\Scanner;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yozh\base\traits\ActiveRecordTrait;

class LanguageSource extends \lajax\translatemanager\models\LanguageSource
{
	const TRANSLATION_CATEGORY_SETTINGS_CONST = 'TRANSLATION_CATEGORY';
	
	use ActiveRecordTrait;
	
	public static function updateDBTranslations( $target, $attributes, $byPk = true, $condition = [], $category = null )
	{
		if( is_string( $attributes ) ) {
			$attributes = [ $attributes ];
		}
		
		if( !isset( $condition['pk'] ) && $byPk !== true && (int)$byPk ) {
			$condition['pk'] = $pk = (int)$byPk;
		}
		
		$changed = [];
		
		if( is_object( $target ) ) {
			
			$className = get_class( $target );
			
			foreach( $attributes as $key => $value ) {
				
				if( is_numeric( $key ) ) {
					$attribute = $value;
					$message   = $target->$attribute;
				}
				else {
					$attribute = $key;
					$message   = $value;
				}
				
				if( $byPk === true && $target instanceof ActiveRecord ) {
					$condition['pk'] = $pk = $target->primaryKey;
				}
				
				static::_updateModelsRecords( $message, $condition, $className, $attribute, $pk ?? null, $category );
			}
			
		}
		else if( is_string( $target ) && class_exists( $target ) ) {
			
			$className = $target;
			
			foreach( $attributes as $attribute => $message ) {
				
				if( is_numeric( $attribute ) ) {
					throw new \yii\base\InvalidParamException( "\$attributes are not set." );
				}
				
				static::_updateModelsRecords( $message, $condition, $className, $attribute, $pk ?? null, $category );
				
			}
		}
		else {
			throw new \yii\base\InvalidParamException( "Class $target does not exists" );
		}
		
	}
	
	protected static function _updateModelsRecords( $message, $condition, $className, $attribute, $pk = null, $category = null )
	{
		$condition = array_merge( [
			'model'     => $className,
			'attribute' => $attribute,
		], ( $condition ?? [] ) );
		
		if( !isset( $condition['pk'] ) && (int)$pk ) {
			$condition['pk'] = $pk;
		}
		
		/*
		if( !isset( $condition['pk'] ) ) {
			throw new \yii\base\InvalidParamException( "There is not set 'primaryKey' for query's condition." );
		}
		*/
		
		if( !isset( $condition['pk'] ) && !isset( $condition['message'] ) ) {
			throw new \yii\base\InvalidParamException( "There is not set 'primaryKey' or 'message' for query's condition." );
		}
		
		$changed = [];
		
		if( $records = LanguageSource::findAll( $condition ) ) {
			
			foreach( $records as $LanguageSource ) {
				
				$category = $category ?? $LanguageSource->category;
				
				if( $LanguageSource->category !== $category || $LanguageSource->message !== $message ) {
					
					$LanguageSource->updateAttributes( [
						'category' => $category ?? $LanguageSource->category,
						'message'  => $message,
					] );
					
					$changed[ $LanguageSource->id ] = true;
					
				}
				
			}
			
			if( count( $changed ) ) {
				LanguageTranslate::updateAll( [ 'changed' => true ], [ 'id' => array_keys( $changed ) ] );
			}
			
		}
		else {
			
			$LanguageSource = new static( [
				'category'  => $category ?? Scanner::CATEGORY_DATABASE,
				'message'   => $message,
				'model'     => $className,
				'attribute' => $attribute,
				'pk'        => strval( $pk ?? null ),
			] );
			
			$LanguageSource->save();
			
		}
		
		return $changed;
		
	}
	
	public function rules( $rules = [] )
	{
		return [
			[ [ 'message' ], 'string' ],
			
			[ [ 'category', 'model', 'attribute', 'pk', ], 'string', 'max' => 255 ],
			[ [ 'category', 'model', 'attribute', 'pk', 'message', ], 'filter', 'filter' => 'trim' ],
			[ [ 'category', 'model', 'attribute', 'pk', 'message', ], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process' ],
		
		];
	}
}