<?php
/**
 * Created by PhpStorm.
 * User: bw_dev
 * Date: 07.09.2018
 * Time: 12:29
 */

namespace yozh\multilingual\controllers;

class LanguageController extends \lajax\translatemanager\controllers\LanguageController
{
	/**
	 * redefine parent
	 */
	public function behaviors()
	{
		return [];
	}
}