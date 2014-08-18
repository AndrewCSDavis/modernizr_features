<?php

	require_once(EXTENSIONS . '/modernizr_features/class/class.os.php');
	require_once(EXTENSIONS . '/modernizr_features/class/class.browser.php');
	
	Class datasourcemodernizr_features extends Datasource{

		public $dsParamROOTELEMENT = 'modernizr_features';
		public $dsParamLIMIT = '1';
		public $dsParamSTARTPAGE = '1';

		public function __construct(&$parent, $env=NULL, $process_params=true){
			parent::__construct($parent, $env, $process_params);
		}

		public function about(){
			return array(
					'name' => 'Modernizr Features',
					'author' => array(
							'name' => 'Andrew Davis',
							'website' => 'tudor50rise@hotmail.co.uk'
						),
					'description' => 'This datasource outputs the users browser info, OS info and Device Features into usable XML data.',
					);
		}

		public function getSource(){
			return NULL;
		}

		public function allowEditorToParse(){
			return FALSE;
		}

		public function execute(array &$param_pool = null) {
			//initiate classes
			include EXTENSIONS . '/modernizr_features/class/class.modernizr.php';
			$result = new XMLElement($this->dsParamROOTELEMENT);
			
			$os = new os();
			$modernizr = new Modernizr();
			$browser = new Browser();			
			$features = (array)$modernizr::boo();
			// root element with attributes
			$windowwidth = explode('|',$_COOKIE['Mwindow']);
			
			$handle = Lang::createHandle($os->getOS());
			if($handle == 'windows-8' && $features->touch == 1){
				$wt = true;
			}else{
				$wt = false;
			}
		
			$result = new XMLElement(
				$this->dsParamROOTELEMENT,
				null,
				array(
					'mobile'=>$browser->isMobile() ? 'yes' : 'no',
					'chromeframe'=>$browser->isChromeFrame() ? 'yes' : 'no',
					'robot'=>$browser->isRobot() ? 'yes' : 'no',
					'windows-touch'=>$wt ? '1' : '0',
					'window-width'=>$windowwidth[0],
					'orientation'=>$windowwidth[1]					
				)
			);
			
			// browser
			$result->appendChild(
				new XMLElement(
					'browser',
					$browser->getBrowser(),
					array(
						'version' => $browser->getVersion(),
						'handle' => Lang::createHandle($browser->getBrowser())
					)
				)
			);
			foreach($features as $feature => $enabled){
				if(is_object($enabled)){																				
					$container = new XMLElement('types');										
					foreach($enabled as $o => $b){						
						$container->appendChild(
							new XMLElement($o,$b)
						);						
					}
					$enabled = $container;
				}				
				if(strpos($feature,'PHP')){
						$result->appendChild(
							new XMLElement(
								'class',
								$enabled
							)
						);		
				}else{
						$result->appendChild(
							new XMLElement(
								$feature,
								$enabled
							)
						);	
				}
							 
			}		
			// platform
			$result->appendChild(
				new XMLElement(
					'operating-system',
					$os->getOS(),
					array(
						'handle' => Lang::createHandle($os->getOS()),
						'platform' => $browser->getPlatform()
					)
				)
			);
			return $result;
		}
	}