<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 07.09.2018
 * Time: 11:59
 */

namespace yozh\multilingual\models;

use Yii;
use yozh\base\models\BaseModel as ActiveRecord;
use yozh\base\traits\ActiveRecordTrait;

class LanguageTranslate extends \lajax\translatemanager\models\LanguageTranslate
{
	use ActiveRecordTrait;
}