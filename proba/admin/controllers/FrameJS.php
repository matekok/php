<?php

class FrameJS {
    public $ini;
    public $funcNames;
    public $functions;
    public $vars;
    public $ready;
    public $ajax;
    public $js;
    public $plugins = array();
    public function __construct($ini) {
        $this->ini = $ini;
        $this->funcNames = array("error_handler" => NULL, "prototypes" => NULL, "jquery_plugins" => NULL, "engine_help" => NULL, "b_submit" => NULL);
        $this->functions = array();
        $this->vars = array();
        $this->ready = array();
        $this->js = array();
        $this->ajax = false;
        $this->plugins[] = 'http://code.jquery.com/jquery-latest.js';
        
    }
    public function makeJS() {
        if ($this->ajax)
            $this->initAjax();
        //$js = "<script language=\"javascript\">\n";
        $js = "";
        foreach ($this->vars as $var)
            $js .= "var " . $var . "\n";
        foreach ($this->js as $var)
            $js .= " " . $var . "\n";
        if (!empty($this->ready) || isset($_SESSION["js_ready"])) {
            $js .= "$(document).ready(function(){\n";
            if (isset($_SESSION["js_ready"])) {
                foreach ($_SESSION["js_ready"] as $r)
                    $js .= $r . "\n";
            }
            if (!empty($this->ready)) {
                foreach ($this->ready as $r)
                    $js .= $r . "\n";
            }
            $js .= "});\n";
        }
        foreach ($this->functions as $func)
            $js .= $func . "\n";
        /*        $js = $this->ini->dict->fill($js);
          if ($this->ini->priceCalcSubDir != "devel") {
          $packer = new JavaScriptPacker($js);
          $js = $packer->pack();
          } */
        return $this->getPlugins()."<script language=\"javascript\">" . $js . "</script>";
        //return $js;
    }
    public function addJs($JS) {
        if (!in_array($JS, $this->js))
            $this->js[] = $JS;
    }
    public function addReady($JS) {
        if (!in_array($JS, $this->ready))
            $this->ready[] = $JS;
    }
    public function addVar($JS) {
        if (!in_array($JS, $this->vars))
            $this->vars[] = $JS;
    }
    public function addFunc($JS) {
        if (!in_array($JS, $this->functions))
            $this->functions[] = $JS;
    }
    private function getPlugins(){
        $ret = '';
        $pre = $this->ini->DAO->CorrectDir();
        foreach ($this->plugins as $value){
            $value2 = (substr( $value, 0, 2 ) === "js")?$pre:'';
            $value2 .= $value;
            $ret .= " <script type=\"text/javascript\" src=\"".$value2."\"></script>\n";
        }
        
        return $ret;
    }
    private function initAjax() {
        $JS = "
        var callAjax = function(method, args, callback, callbefore) {
	var argString = \"\";
        args['sess_id'] = '" . session_id() . "';
	for(arg in args){
		argString += \"&\" + arg + \"=\" + args[arg];
	}
	if (is_defined(callbefore)) {
		callbefore({
			'method':method,
			'args':args,
			'callback':callback
		});
	}
	if(is_defined(method)){
		$.ajax({
                        type:  \"POST\",
                        data: args,   
			url: \"controllers/FrameAjax.php\",
			complete: function(data){
				if (is_defined(callback)) {
					callback($.parseJSON(data.responseText));
				}
			}
		});
	}else{
		console.log(\"No method provided! Aborted direct call\");
	}
};
// simple wrapper to allow argument passing via an object
var direct_config = function(config) {
	direct(config['method'], config['args'], config['callback'], config['callbefore']);
}
var is_defined = function(what){
	return (what !== undefined && what !== null);
}";
        $this->addFunc($JS);
    }
}
?>