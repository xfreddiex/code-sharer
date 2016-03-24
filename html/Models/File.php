<?php

namespace Models;

use Models\Base\File as BaseFile;
use Propel\Runtime\Connection\ConnectionInterface;

/**
 * Skeleton subclass for representing a row from the 'file' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 */
class File extends BaseFile
{
	public function preSave(ConnectionInterface $con = null){
		return $this->validate();
	}

	public function getContent(){
		$content = stream_get_contents($this->content);
		return $content;
	}

	public function getExtension(){
		$ext = end(explode(".", $this->name));
		return $ext;
	}

}
