<?php
namespace Controllers;

use Controllers\Base;

class Home extends Base{
	function instructions($params){
		$this->data['title'] = 'GG';
		$this->data['keywords'] = 'GG';
		$this->data['description'] = 'GG';
	}
}