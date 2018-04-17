<?php
class ClassAutoLoader {
	
	private $dirs = array();
        public $DAO;
        public $JS;
        public $SQL;
        private $menu;
        private $doct;
        public $css = array();
	public function __construct(){
                spl_autoload_register(array($this, 'loader'));
                
	}
	public function register(){
		$this->dirs = array(
			__DIR__ . '/../controllers/',
                        __DIR__ . '/../sql/',
			__DIR__ . '/../moduls/',
                        __DIR__ . '/../pages/',
                        __DIR__ . '/../template/'                    
		);
	}
	public function loader($classname){
		foreach ($this->dirs as $dir){
			$file = "{$dir}{$classname}.php";
			if(is_readable($file)){
				require_once $file;
				return;
			}
		}
	}
        public function doIni(){
            //$error = new errorHandler($this);
            //print $error;
            $this->DAO = new FrameDAO($this);
            $this->JS = new FrameJS($this);
            //$this->SQL = new FrameSQL($this);
            //$this->menu = new Menu($this);
            //$this->dict = new FrameDict($this);
        }
        public function CallFunc(){
            
            //$this->DAO->login();
            //$this->DAO->setSessionValue('lat', null);
            //$this->DAO->setSessionValue('lon', null);
            //if(!$this->DAO->getSessionValue('lat') || !$this->DAO->getSessionValue('lon'))$this->DAO->getLocation();
            //mindig utolsónak kell lennie
            //echo $this->DAO->language();
            $this->DAO->callFunc();
            echo $this->DAO->getPage($this);
//            echo $this->DAO->dump($_SESSION, 'SESSION');
//            echo $this->DAO->dump($this, 'this');
        }
}

//$ClassAutoLoader->CallDAO();
?>