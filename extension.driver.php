<?php
	
	
	Class extension_modernizr_features extends Extension{

		public function getSubscribedDelegates() {
			return array(				
				array(
					'page' => '/system/preferences/',
					'delegate' => 'Save',
					'callback' => 'savePreferences'
				),
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendParamsPostResolve',
					'callback' => 'addParameters'
				)
			);
		}
		
		
	
		public function savePreferences($context) {
	
		}
		
	
		public function addParameters($context) {
			
        }

	}
?>