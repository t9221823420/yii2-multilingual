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
use yozh\base\models\BaseModel as ActiveRecord;
use yozh\base\traits\ActiveRecordTrait;

class LanguageSource extends \lajax\translatemanager\models\LanguageSource
{
	use ActiveRecordTrait;
	
	public static function updateTranslation( $target, $attributes, $byPk = true, $condition = [], $category = null )
	{
		if( is_string( $attributes ) ) {
			$attributes = [ $attributes ];
		}
		
		$changed = [];
		
		if( is_object( $target ) ) {
			
			$className = ( new\ReflectionObject( $target ) )->getShortName();
			
			foreach( $attributes as $attribute ) {
				
				$condition = [
					'model'     => $className,
					'attribute' => $attribute,
				];
				
				if( $byPk === true && $target instanceof ActiveRecord ) {
					$condition['pk'] = $pk = $target->primaryKey;
				}
				else if( (int)$byPk ) {
					$condition['pk'] = $pk = (int)$byPk;
				}
				
				if( $records = static::findAll( $condition ) ) {
					
					$changed = array_merge( $changed, static::_updateRecords( $records, $target->$attribute, $category ) );
					
				}
				else {
					
					$LanguageSource = new static( [
						'category'  => $category ?? Scanner::CATEGORY_DATABASE,
						'message'   => $target->$attribute,
						'model'     => $className,
						'attribute' => $attribute,
						'pk'        => $pk ?? null,
					] );
					
					$LanguageSource->save();
					
					$records = [ $LanguageSource ];
					
				}
				
			}
			
		}
		else if( is_string( $target ) && class_exists( $target ) ) {
			
			$className = $target;
			
			foreach( $attributes as $attribute => $message ) {
				
				$condition = [
					'model'     => $className,
					'attribute' => $attribute,
				];
				
				if( (int)$byPk ) {
					$condition['pk'] = $pk = (int)$byPk;
				}
				
				if( $records = LanguageSource::findAll( $condition ) ) {
					
					$changed = array_merge( $changed, static::_updateRecords( $records, $message, $category ) );
					
				}
				else {
					
					$LanguageSource = new static( [
						'category'  => $category ?? Scanner::CATEGORY_DATABASE,
						'message'   => $message,
						'model'     => $className,
						'attribute' => $attribute,
						'pk'        => $pk ?? null,
					] );
					
					$LanguageSource->save();
					
					$records = [ $LanguageSource ];
					
				}
				
			}
		}
		else {
			throw new \yii\base\InvalidParamException( "Class $target does not exists" );
		}
		
	}
	
	protected static function _updateRecords( $records, $message, $category = null )
	{
		$changed = [];
		
		foreach( $records as $LanguageSource ) {
			
			$LanguageSource->updateAttributes( [
				'category' => $category ?? $LanguageSource->$category,
				'message'  => $message,
			] );
			
			$changed[ $LanguageSource->id ] = true;
		}
		
		if( count( $changed ) ) {
			LanguageTranslate::updateAll( [ 'changed' => true ], [ 'id' => $changed ] );
		}
		
		return $changed;
		
	}
}