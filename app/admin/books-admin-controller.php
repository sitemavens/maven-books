<?php

namespace MavenBooks\Admin;

abstract class BooksAdminController extends \Maven\Core\Ui\AdminController{
	
	public function __construct(){
		
		parent::__construct( \MavenBooks\Settings\BooksRegistry::instance() );
		
		// We set the message manager and the key generator
		//$this->setMessageManager( \Maven\Core\Message\MessageManager::getInstance( new \Maven\Core\Message\UserMessageKeyGenerator() ) );
	}
	
}