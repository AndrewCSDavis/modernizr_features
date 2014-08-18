<?php

class Modernizr {

  
 // static $modernizr_js = '/m/modernizr.js';
  static $key = 'Modernizr';
  
  static function boo() {
    $key = self::$key;
    /*if (session_start() && isset($_SESSION) && isset($_SESSION[$key])) {
      return $_SESSION[$key];
    } else*/
	if (isset($_COOKIE) && isset($_COOKIE[$key])) {
      $modernizr = self::_ang($_COOKIE[$key]);	  
	 
      /*if (isset($_SESSION)) {
        $_SESSION[$key] = $modernizr;
      }*/
	    
      return $modernizr;
    } else {
      print "<html><head>".
	  "<script type='text/javascript'>";
		readfile(EXTENSIONS.'/modernizr_features/class/m.js');
		print "if(screen.width > screen.height){".
			"var orientation = 'landscape'".
		"}else if(screen.width < screen.height){".
			"var orientation = 'portrait'".
		"}";
		print "document.cookie = 'Mwindow='+screen.width+'|'+orientation;";
		print self::_mer() .
		
	  "</script>".
	  "</head><body></body></html>";
      exit;
    }	
  }
  static function _mer() {
    return "".
      "var m=Modernizr,c='';".	  
      "for(var f in m){".
        "if(f[0]=='_'){continue;}".
        "var t=typeof m[f];".
        "if(t=='function'){continue;}".
        "c+=(c?'|':'Modernizr=')+f+':';".
        "if(t=='object'){".
          "for(var s in m[f]){".
            "c+='/'+s+':'+(m[f][s]?'1':'0');".
          "}".
        "}else{".
          "c+=m[f]?'1':'0';".
        "}".
		"}".	  
      "c+=';path=/';".	  
      "try{".
        "document.cookie=c;".
        "document.location.reload();".
      "}catch(e){}".
    "";
  }
  
  static function _ang($cookie) {
    $modernizr = new Modernizr();
    foreach (explode('|', $cookie) as $feature) {
	
      list($name, $value) = explode(':', $feature, 2);
      if ($value[0]=='/') {
        $value_object = new stdClass();
        foreach (explode('/', substr($value, 1)) as $sub_feature) {
          list($sub_name, $sub_value) = explode(':', $sub_feature, 2);
          $value_object->$sub_name = $sub_value;
        }
        $modernizr->$name = $value_object;
      } else {
        $modernizr->$name = $value;
      }
    }
	
    return $modernizr;
  }
  
}

$modernizr = Modernizr::boo();

?>