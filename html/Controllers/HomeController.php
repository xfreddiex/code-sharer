<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Pack;
use Models\PackQuery;

class HomeController extends BaseController{


	public function __construct(){
		parent::__construct();

		$this->data["items"] = null;
	}
	protected function index(){
		$this->viewFile($this->template);
	}

	protected function search(){
		if(isset($_GET["q"]) && $_GET["q"] != ""){
			
			$q = urldecode($_GET["q"]);
			$page = 1;
			$perPage = 10;

			$this->data["q"] = $q;
			$this->data["search"] = "pack";

			if(isset($_GET["search"])){
				$this->data["search"] = $_GET["search"];
			}
			if(isset($_GET["page"]) && $_GET["page"] >= 1){
				$page = $_GET["page"];
			}

			$qParts = preg_split("/\s+/", $q);
			for($i = 0; $i < count($qParts); $i++){
				$qParts[$i] = $qParts[$i]."%";
			}
			$qMatch = $q."*";

			if($this->data["search"] == "user"){

				for($i = 0; $i < count($qParts); $i++){
					if($i > 0)
						$likeQuery .= " OR ";
					$likeQuery .= 'user.username LIKE ?';
				}
				for($i = 0; $i < count($qParts); $i++){
					$likeQuery .= ' OR user.name LIKE ?';
				}
				for($i = 0; $i < count($qParts); $i++){
					$likeQuery .= ' OR user.surname LIKE ?';
				}
				$qParts = array_merge($qParts, $qParts, $qParts);

				$this->data["items"] = UserQuery::create()->where("MATCH(user.username, user.name, user.surname) AGAINST(? IN BOOLEAN MODE)", $qMatch)->_or()->where($likeQuery, $qParts)->paginate($page, $perPage);
				
				$this->viewFile($this->template);
				return;
			}

			if($this->data["search"] == "pack"){

				for($i = 0; $i < count($qParts); $i++){
					if($i > 0)
						$likeQuery .= " OR ";
					$likeQuery .= 'pack.name LIKE ?';
				}
				if(count($qParts) == 1)
					$qParts = $qParts[0];

				$this->data["items"] = PackQuery::create()->condition("cond1", "MATCH(pack.name, pack.description) AGAINST(? IN BOOLEAN MODE)", $qMatch)->condition("cond2", "pack.private=false")->condition("cond3", $likeQuery, $qParts)->combine(array("cond1", "cond3"), "or", "cond13")->where(array("cond13", "cond2"), "and")->paginate($page, $perPage);
				$this->viewFile($this->template);
				return;
			}
			else
				$this->redirect("/");
		}
		else{
			$this->redirect("/");
		}
	}	
}