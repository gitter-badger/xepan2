<?php

/**
* description: InstalledApplication defines installed xEpan Applications for any Epan.
* Based on Free/Shareware or Paid user can access application. 
* 
* @author : Gowrav Vishwakarma
* @email : gowravvishwakarma@gmail.com, info@xavoc.com
* @website : http://xepan.org
* 
*/

class Model_Epan_InstalledApplication extends Model_Table{
	public $table='installed_application';

	function init(){
		parent::init();
		
		$this->hasOne('Epan');
		$this->hasOne('Application');

		$this->addField('installed_on')->type('datetime')->defaultValue($this->api->now);
		$this->addField('valid_till')->type('datetime')->defaultValue($this->api->now);

		$this->addField('is_active')->type('boolean')->defaultValue(true);
		$this->addExpression('is_valid')->set(true); // TODO set based on actual condition, FREE, TIME BASED, LICENSE VALID etc.
	}
}
