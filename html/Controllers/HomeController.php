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

			$this->data["search"] = "pack";
			if(isset($_GET["search"])){
				$this->data["search"] = $_GET["search"];
			}
			if(isset($_GET["page"]) && $_GET["page"] >= 1){
				$page = $_GET["page"];
			}

			$this->data["q"] = $q;

			if($this->data["search"] == "user"){
				$this->data["items"] = UserQuery::create()->where("MATCH(user.username, user.name, user.surname) AGAINST(? IN BOOLEAN MODE)", $q)->paginate($page, $perPage);
				
				$this->viewFile($this->template);
				return;
			}
			if($this->data["search"] == "pack"){
				$this->data["items"] = PackQuery::create()->where("MATCH(pack.name, pack.description) AGAINST(? IN BOOLEAN MODE)", $q)->_and()->where("pack.private=false")->paginate($page, $perPage);

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