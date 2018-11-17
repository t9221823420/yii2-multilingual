<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 07.09.2018
 * Time: 11:59
 */

namespace yozh\multilingual\models;

use Yii;
use yozh\base\models\BaseActiveRecord as ActiveRecord;
use yozh\base\traits\ActiveRecordTrait;

class Language extends \lajax\translatemanager\models\Language
{
	use ActiveRecordTrait;
}