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
		$this->data['title'] = 'Starling';
		$this->data['keywords'] .= 'home, homepage, registration, sign up';
		$this->data['description'] .= 'Main page of this application.';
		$this->viewFile($this->template);
	}

	protected function search(){
		$this->data['title'] = 'Starling';
		$this->data['keywords'] .= 'search, results';
		$this->data['description'] .= 'Results of searching.';
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
			$likeQuery = "";

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

				$this->data["items"] = UserQuery::create()->condition("cond1", "MATCH(user.username, user.name, user.surname) AGAINST(? IN BOOLEAN MODE)", $qMatch)->condition("cond2", "user.deleted_at IS NULL")->condition("cond3", $likeQuery, $qParts)->combine(array("cond1", "cond3"), "or", "cond13")->where(array("cond13", "cond2"), "and")->paginate($page, $perPage);
				
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