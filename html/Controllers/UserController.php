<?php
namespace Controllers;

use Controllers\BaseController;
use Models\User;
use Models\UserQuery;
use Models\Identity;
use Models\IdentityQuery;

class UserController extends BaseController{

	public function __construct(){
		parent::__construct();
		$this->data["userAuthorized"] = false;
		$this->addBefore("update", array("userLogged", "userAuthorized"));
		$this->addBefore("profile", array("userLogged"));
		$this->addBefore("settings", array("userLogged"));
		$this->addBefore("updateAvatar", array("userLogged"));
		$this->addBefore("delete", array("userLogged", "userAuthorized"));
	}

	protected function show($params){
		$this->data["user"] = UserQuery::create()->findOneByUsername(urldecode($params["username"]));
		if($this->data["user"] == $this->data["loggedUser"])
			$this->redirect("/profile");
		if($this->data["user"]){
			if($this->data["user"]->getDeletedAt()){
				$this->sendFlashMessage("User ".$this->data["user"]->getUsername()." was deleted on ".$this->data["user"]->getDeletedAt("j M o").".", "error");
				$this->redirect("/404");	
			}
			if(!$this->data["user"]->getEmailConfirmedAt()){
				$this->sendFlashMessage("User ".$this->data["user"]->getUsername()." is not active yet.", "error");
				$this->redirect("/");	
			}
			$this->viewFile($this->template);
		}
		else{
			$this->sendFlashMessage('User "'.$params["username"].'" does not exists.', "error");
			$this->redirect("/");
		}
	}

	protected function profile(){
		$this->data["foreignPacks"] = $this->data["loggedUser"]->getForeignPacks();
		$this->viewFile($this->template);	
	}

	protected function settings(){
		$this->viewFile($this->template);	
	}

	protected function delete(){
		$this->data["loggedUser"]->setDeletedAt(time());
		$this->data["loggedUser"]->save();
		unset($_SESSION["userId"]);
		if(isset($_COOKIE["identityId"])){
			$identity = IdentityQuery::create()->findPK($_COOKIE["identityId"]);
			if($identity){
				$identity->delete();
				setcookie("identityId", "", time() - 86400);
				setcookie("identityToken", "", time() - 86400);
			}
		}
		$this->sendFlashMessage("Your account has been deleted.", "info");
		$this->redirect("/");
	}

	protected function emailConfirm($params){
		$token = $params["token"];
		$user = UserQuery::create()->findOneByUsername(urldecode($params["username"]));
		
		if($user){
			if($user->getEmailConfirmedAt()){
				$this->sendFlashMessage("You have already confirmed your email.", "info");
			}
			else if($user->checkEmailConfirmToken($token)){
				$user->setEmailConfirmedAt(time());
				$user->save();
				$this->sendFlashMessage($user->getUsername().", you have confirmed your email. Now you can sign in.", "success");
			}
			else
				$this->sendFlashMessage($user->getUsername().', your email has not been confirmed. <a class="link" href="/user/'.$user->getUsername().'/send-email-confirm-email">Send new email confirm link?</a>', "error");
		}
		else{
			$this->sendFlashMessage('User "'.$params["username"].'" does not exists.', "error");
		}
		$this->redirect("/");
	}

	protected function sendEmailConfirmEmail($params){
		$user = UserQuery::create()->findOneByUsername(urldecode($params["username"]));
		
		if($user){
			if($user->getEmailConfirmedAt()){
				$this->sendFlashMessage("You have already confirmed your email.", "info");
			}
			else{
				$emailConfirmToken = uniqid().generateRandomString(19);
				$user->setEmailConfirmedAt(null);
				$user->setEmailConfirmToken($emailConfirmToken);
				$user->save();

				$body = '<p>You have changed your email address.</p><br /><p>Please virify your email address by clicking this link:</p><a href="'.$this->siteURL.'/user/'.$user->getUsername().'/email-confirm/'.urlencode($emailConfirmToken).'">'.$this->siteURL.'/user/'.$user->getUsername().'/email-confirm/'.urlencode($emailConfirmToken).'</a>';

				$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
				->setUsername($this->emailAddress)
				->setPassword($this->emailPassword);

				$mailer = \Swift_Mailer::newInstance($transport);

				$message = \Swift_Message::newInstance()
				->setSubject('Email verification')
				->setFrom(array($this->emailAddress => 'Starling admin'))
				->setTo(array($user->getEmail() => $user->getUsername()))
				->setBody($body, 'text/html');

				$result = $mailer->send($message);
				$this->sendFlashMessage("We have sent you email confirmation link to your email address.", "success");
			}
		}
		else{
			$this->sendFlashMessage('User "'.$params["username"].'" does not exists.', "error");
		}
		$this->redirect("/");

	}

	protected function restoreAccount($params){
		$token = $params["token"];
		$user = UserQuery::create()->findOneByUsername(urldecode($params["username"]));
		
		if($user){
			if(!$user->getDeletedAt()){
				$this->sendFlashMessage("Your account has not been deleted.", "info");
			}
			else if($user->checkAccountRestoreToken($token)){
				$user->setDeletedAt(null);
				$user->save();
				$this->sendFlashMessage($user->getUsername().", you have restored your account. Now you can sign in.", "success");
			}
			else
				$this->sendFlashMessage($user->getUsername().', your account has not been restored. <a class="link" href="/user/'.$user->getUsername().'/send-restore-account-email">Send new restore link?</a>', "error");
		}
		else{
			$this->sendFlashMessage('User "'.$params["username"].'" does not exists.', "error");
		}
		$this->redirect("/");
	}

	protected function sendRestoreAccountEmail($params){
		$user = UserQuery::create()->findOneByUsername(urldecode($params["username"]));
		
		if($user){
			if(!$user->getDeletedAt()){
				$this->sendFlashMessage("You have not deleted your account.", "info");
			}
			else{
				$accountRestoreToken = uniqid().generateRandomString(19);
				$user->setAccountRestoreToken($accountRestoreToken);
				$user->save();

				$body = '<p>Restore your account by clicking this link:</p><a href="'.$this->siteURL.'/user/'.$user->getUsername().'/restore-account/'.urlencode($accountRestoreToken).'">'.$this->siteURL.'/user/'.$user->getUsername().'/restore-account/'.urlencode($accountRestoreToken).'</a>';

				$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
				->setUsername($this->emailAddress)
				->setPassword($this->emailPassword);

				$mailer = \Swift_Mailer::newInstance($transport);

				$message = \Swift_Message::newInstance()
				->setSubject('Account restore')
				->setFrom(array($this->emailAddress => 'Starling admin'))
				->setTo(array($user->getEmail() => $user->getUsername()))
				->setBody($body, 'text/html');

				$result = $mailer->send($message);

				$this->sendFlashMessage("Link to restore your account has been sent to account email address.", "success");
			}
		}
		else{
			$this->sendFlashMessage('User "'.$params["username"].'" does not exists.', "error");
		}
		$this->redirect("/");
	}

	protected function resetPassword($params){
		$token = $params["token"];
		$user = UserQuery::create()->findOneByUsername(urldecode($params["username"]));
		
		if($user){
			if($user->checkPasswordResetToken($token)){
				unset($_SESSION["userId"]);
				$newPassword = uniqid();
				$user->setPassword($newPassword);
				$user->save();

				$body = '<p>New password for Starling account.</p><br />Username: '.$user->getUsername().'<br />Password: '.$newPassword;

				$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
				->setUsername($this->emailAddress)
				->setPassword($this->emailPassword);

				$mailer = \Swift_Mailer::newInstance($transport);

				$message = \Swift_Message::newInstance()
				->setSubject('Password reset')
				->setFrom(array($this->emailAddress => 'Starling admin'))
				->setTo(array($user->getEmail() => $user->getUsername()))
				->setBody($body, 'text/html');

				$result = $mailer->send($message);

				$this->sendFlashMessage("New password has been sent.", "success");
			}
			else
				$this->sendFlashMessage('Your password has not been reseted. <a class="link" href="/user/'.$user->getEmail().'/send-reset-password-email">Send new reset link?</a>', "error");
		}
		else{
			$this->sendFlashMessage('User "'.$params["username"].'" does not exists.', "error");
		}
		$this->redirect("/");
	}

	protected function sendPasswordResetEmail($params){
		$user = UserQuery::create()->findOneByEmail(urldecode($params["email"]));
		
		if($user){
			$passwordResetToken = uniqid().generateRandomString(19);
			$user->setPasswordResetToken($passwordResetToken);
			$user->save();

			$body = '<p>Reset your password by clicking this link:</p><a href="'.$this->siteURL.'/user/'.$user->getUsername().'/reset-password/'.urlencode($passwordResetToken).'">'.$this->siteURL.'/user/'.$user->getUsername().'/reset-password/'.urlencode($passwordResetToken).'</a>';

			$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
			->setUsername($this->emailAddress)
			->setPassword($this->emailPassword);

			$mailer = \Swift_Mailer::newInstance($transport);

			$message = \Swift_Message::newInstance()
			->setSubject('Password reset')
			->setFrom(array($this->emailAddress => 'Starling admin'))
			->setTo(array($user->getEmail() => $user->getUsername()))
			->setBody($body, 'text/html');

			$result = $mailer->send($message);

			$this->sendFlashMessage("Link to reset your password has been sent to account email address.", "success");
		}
		else{
			$this->sendFlashMessage('Email "'.$params["email"].'" does not exists.', "error");
		}
		$this->redirect("/");
	}

	protected function update(){
		$user = $this->data["loggedUser"];
		$oldEmail = $user->getEmail();
		$emailChanged = false;

		if(isset($_POST["newName"]))
			$user->setName($_POST["newName"]);
		if(isset($_POST["newSurname"]))
			$user->setSurname($_POST["newSurname"]);
		if(isset($_POST["newEmail"]) && $_POST["newEmail"] != $user->getEmail()){
			$user->setEmail($_POST["newEmail"]);
			$emailChanged = true;
		}
		if(isset($_POST["newPassword"]) && $_POST["newPassword"])
			$user->setPassword($_POST["newPassword"]);

		if(!$user->save()){
    		$failures = $user->getValidationFailures();
			if(count($failures) > 0){
				foreach($failures as $failure){
					$this->sendFlashMessage("User data has not been changeg. ".$failure->getMessage(), "error");
				}
			}
		}
		else{
			$this->sendFlashMessage("User data has been successfuly updated.", "success");
			
			if($emailChanged){
				$emailConfirmToken = uniqid().generateRandomString(19);
				$user->setEmailConfirmedAt(null);
				$user->setEmailConfirmToken($emailConfirmToken);
				$user->save();

				$body = '<p>You have changed your email address.</p><br /><p>Please virify your new email address by clicking this link:</p><a href="'.$this->siteURL.'/user/'.$user->getUsername().'/email-confirm/'.urlencode($emailConfirmToken).'">'.$this->siteURL.'/user/'.$user->getUsername().'/email-confirm/'.urlencode($emailConfirmToken).'</a>';

				$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
				->setUsername($this->emailAddress)
				->setPassword($this->emailPassword);

				$mailer = \Swift_Mailer::newInstance($transport);

				$message = \Swift_Message::newInstance()
				->setSubject('Email verification')
				->setFrom(array($this->emailAddress => 'Starling admin'))
				->setTo(array($oldEmail => $user->getUsername()))
				->setBody($body, 'text/html');

				$result = $mailer->send($message);
				$this->sendFlashMessage("You have changed your email adress. We have sent you confirmation link to your <strong>old</strong> email address.", "info");

				unset($_SESSION["userId"]);
				if(isset($_COOKIE["identityId"])){
					$identity = IdentityQuery::create()->findPK($_COOKIE["identityId"]);
					if($identity){
						$identity->delete();
						setcookie("identityId", "", time() - 86400);
						setcookie("identityToken", "", time() - 86400);
					}
				}

				$this->redirect("/");
			}
		}
		$this->redirect("/settings");
	}

	protected function updateAvatar(){
		if(isset($_POST["newAvatar"])){
			$data = explode(',', $_POST["newAvatar"]);
			if(count($data) == 2 && $data[0] == "data:image/png;base64" && base64_decode($data[1])){
				$img = imagecreatefromstring(base64_decode($data[1]));
				$nameToDelete = $this->data["loggedUser"]->getAvatarPath();
				$name = md5(uniqid()).".png";
				$this->data["loggedUser"]->setAvatarPath($name)->save();

				$dir = "Includes/images/avatars/250x250/";
				if(is_file($dir.$nameToDelete))
					unlink($dir.$nameToDelete);
				imagepng(resizeImg($img, 250, 250), $dir.$name);
		
				$dir = "Includes/images/avatars/40x40/";
				if(is_file($dir.$nameToDelete))
					unlink($dir.$nameToDelete);
				imagepng(resizeImg($img, 40, 40), $dir.$name);

				$this->sendFlashMessage("Your avatar has been successfuly changed. ", "success");
				$this->redirect($this->data["referersURI"]);
			}
			else
				setHTTPStatusCode("400");
		}	
		else		
			setHTTPStatusCode("400");
	}

	protected function create(){
		if(isset($_POST["email"]) && isset($_POST["username"]) && isset($_POST["password"])){
			$user = new User();
			$emailConfirmToken = uniqid().generateRandomString(19);
			$user->fromArray(array(
				"Email" => $_POST["email"], 
				"Username" => $_POST["username"], 
				"Password" => $_POST["password"],
				"EmailConfirmToken" => $emailConfirmToken
			));
			
			if(!$user->save()){
    			$failures = $user->getValidationFailures();
				if(count($failures) > 0){
					foreach($failures as $failure){
						$this->sendFlashMessage("You have not been signed up. ".$failure->getMessage(), "error");
					}
				}
			}
			else{
				$this->sendFlashMessage('You have been successfuly signed up. Please confirm your email address, we have send confirmation link. <a class="link" href="/user/'.$user->getUsername().'/send-email-confirm-email">Send new email confirm link?</a>', "success");

				$body = '<p>You have created new account on Starling.</p><br /><p>Please virify your email address by clicking this url:</p><a href="'.$this->siteURL.'/user/'.$user->getUsername().'/email-confirm/'.urlencode($emailConfirmToken).'">'.$this->siteURL.'/user/'.$user->getUsername().'/email-confirm/'.urlencode($emailConfirmToken).'</a>';

				$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
				->setUsername($this->emailAddress)
				->setPassword($this->emailPassword);

				$mailer = \Swift_Mailer::newInstance($transport);

				$message = \Swift_Message::newInstance()
				->setSubject('Email verification')
				->setFrom(array($this->emailAddress => 'Starling admin'))
				->setTo(array($user->getEmail() => $user->getUsername()))
				->setBody($body, 'text/html');

				$result = $mailer->send($message);
			}
			$this->redirect("/");
		}
		else
			setHTTPStatusCode("400");
	}

	protected function signIn(){	
		if(isset($_POST["username"]) && isset($_POST["password"])){
			$user = UserQuery::create()
				->findOneByUsername($_POST["username"]);
			if(!$user){
				$this->sendFlashMessage("You have not been signed in. User does not exist.", "error");
			}
			else if($user->getDeletedAt()){
				$this->sendFlashMessage("Your account was deleted on ".$user->getDeletedAt("j M o").'. <a class="link" href="/user/'.$user->getUsername().'/send-restore-account-email">Send restore link?</a>', "error");
				$this->redirect("/404");	
			}
			else if($user->checkPassword($_POST["password"])){
				$_SESSION["userId"] = $user->getId();
				if(isset($_POST["rememberMe"])){
					if(isset($_COOKIE["identityId"])){
						$identity = IdentityQuery::create()
							->filterById($_COOKIE["identityId"])
							->delete();
					}
					$token = generateRandomString(32);
					$identity = new Identity();
					$identity->setToken($token)
						->setUser($user)
						->save();
					setcookie("identityId", $identity->getId(), time() + (86400 * 120));
					setcookie("identityToken", $token, time() + (86400 * 120));
				}
			}
			else
				$this->sendFlashMessage("You have not been signed in. You entered wrong password.", "error");
			$this->redirect($this->data["referersURI"]);
		}
		else
			setHTTPStatusCode("400");
	}

	protected function signOut(){
		unset($_SESSION["userId"]);
		if(isset($_COOKIE["identityId"])){
			$identity = IdentityQuery::create()->findPK($_COOKIE["identityId"]);
			if($identity){
				$identity->delete();
				setcookie("identityId", "", time() - 86400);
				setcookie("identityToken", "", time() - 86400);
			}
		}
		$this->redirect("/");
	}

	protected function validateOne(){
		setContentType("json");
		$user = new User();
		$given = array_keys($_POST);
		$response["error"] = null;
		if(count($given) == 1){
			if($given[0] == "username"){
				$user->setUsername($_POST["username"]);
			}
			else if($given[0] == "password"){
				$user->setPassword($_POST["password"]);
			}
			else if($given[0] == "email"){
				$user->setEmail($_POST["email"]);
			}
			else if($given[0] == "name"){
				$user->setName($_POST["name"]);
			}
			else if($given[0] == "surname"){
				$user->setSurname($_POST["surname"]);
			}
			else{
				setHTTPStatusCode("400");
				return;
			}
			if(!$user->validate()){
				foreach($user->getValidationFailures() as $failure){
					if($given[0] == $failure->getPropertyPath()){
						$response["error"] = array(
							"name" => $failure->getPropertyPath(),
							"message" => $failure->getMessage()
						);
					}
				}
			}
			$this->viewString(json_encode($response));
		}
		else
			setHTTPStatusCode("400");
	}

}