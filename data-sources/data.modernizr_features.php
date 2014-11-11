<?php

	require_once(EXTENSIONS . '/modernizr_features/class/class.os.php');
	require_once(EXTENSIONS . '/modernizr_features/class/class.browser.php');
	require_once EXTENSIONS . '/modernizr_features/class/class.modernizr.php';
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
			$currenturl = $this->_env['param']['current-url'];
			$root = $this->_env['param']['root'];
			//var_dump($this->get('current-url'));
			
			$result = new XMLElement($this->dsParamROOTELEMENT);
			
			$os = new os();
			$modernizr = new Modernizr();
			$browser = new Browser();			
			$features = (array)$modernizr::boo($root.'/ajax');
			// root element with attributes
			
			//$windowwidth = $_COOKIE['device_dimensions'];
			
			$windowwidth = explode('x',$_COOKIE['Mwindow']);
			unset($_COOKIE['Modernizr']);
			unset($_COOKIE['Modernizr']);
			setcookie("Modernizr", "", time()-3600);
			//setcookie("Mwindow", "", time()-3600);
			
			$handle = Lang::createHandle($os->getOS());
			if($handle == 'windows-8' && $features->touch == 1){
				$wt = true;
			}else{
				$wt = false;
			}			
			$windowwidth[0] = (int) $windowwidth[0];
			/*if($features['mouse'] == 1 && $windowwidth[0] > 1140){
				$devicetype = 'desktop';
			}
			
			if($features['mouse'] == 1 && $features['touch'] == 0 && $windowwidth[0] <= 1140){
				$devicetype = 'desktop';
			}*/					
			if($features['retina'] == 1){
				$windowwidth[3] = ($windowwidth[0] / 2);
			}
			
			if($features['touch'] == 1){
				if($features['mouse'] == 1){
					if($features['retina'] == 1){
							if($browser->getPlatform() == 'iPad'){
								$devicetype = 'ipad'; // ipad
							}elseif($browser->getPlatform() == 'iPhone'){
								$devicetype = 'handheld'; // Android tablets
							}elseif($browser->getPlatform() == 'Android' && $windowwidth[0] <= 400){
								$devicetype = 'handheld'; // Android tablets
							}elseif($browser->getPlatform() == 'Android' && $windowwidth[0]  > 400){
								$devicetype = 'tablet'; // Android tablets
							}elseif($browser->getPlatform() == 'Windows'){
								$devicetype = 'desktop-touch'; // now checking for windows 8  desktop touch
							}
					}else{
							if($browser->getPlatform() == 'iPad'){
								$devicetype = 'ipad-mini'; // ipad
							}elseif($browser->getPlatform() == 'iPhone'){
								$devicetype = 'handheld'; // Android tablets
							}elseif($browser->getPlatform() == 'Android'){
								$devicetype = 'tablet'; // Android tablets
							}elseif($browser->getPlatform() == 'Windows'){
								$devicetype = 'desktop-touch'; // now checking for windows 8  desktop touch
							}elseif(isset($windowwidth[3]) && $windowwidth[3] <= 1000){
								$devicetype = 'tablet';
							}elseif(isset($windowwidth[3]) && $windowwidth[3] <= 2000){
								$devicetype = 'large-tablet';
							}elseif($windowwidth[0] <= 400){
								$devicetype = 'handheld'; // phones that are less than 400 px wide and have no OS
							}
					}
				}else{
					if(strpos('iPad',$browser->getPlatform())){
						$devicetype = 'tablet'; // ipad
					}elseif(strpos('windows',Lang::createHandle($os->getOS()))){
						$devicetype = 'desktop-touch'; // now checking for windows 8  desktop touch
					}elseif(strpos('linux',Lang::createHandle($os->getOS()))){
						$devicetype = 'tablet'; // Android tablets
					}elseif(isset($windowwidth[3]) && $windowwidth[3] <= 1000){
						$devicetype = 'tablet';
					}elseif($windowwidth[0] <= 400){
						$devicetype = 'handheld'; // phones that are less than 400 px wide and have no OS
					}
				}
			}else{
				if($features['mouse'] == 1){
					$devicetype = 'desktop';				
				}
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
					'real-width'=> isset($windowwidth[3]) ? $windowwidth[3] : 'not-retina',
					'orientation'=>$windowwidth[1],
					
					'device-type'=>$devicetype
					
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
				if($feature != '64bit' && $feature != '32bit'){
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