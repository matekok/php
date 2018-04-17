<?php

class FrameDAO {

    public $ini;
    public $HTML;
    public $pageTitle;
    public $metaData = array();

    public function __construct($ini = NULL) {
        $this->ini = $ini;
        $this->ini->engine = 'admin';
        $this->pageTitle = 'Takács Zsolt Pincészete admin';
        $this->metaData['charset'] = 'UTF-8';
        //store _get & _post
        $this->StoreParamsToSession();
    }

    private function StoreParamsToSession() {
        $blacklist = array('test', 'menuitem', 'user_id', 'params', 'method', 'sess_id');
        foreach ($_GET as $key => $value) {
            if (!in_array($key, $blacklist)) {
                $this->setSessionValue($key, $value);
            }
        }
        foreach ($_POST as $key => $value) {
            if (!in_array($key, $blacklist)) {
                $this->setSessionValue($key, $value);
            }
        }
    }

    public function getPage() {

        $ret = "<html><head>";
        $ret .= "<title>" . $this->pageTitle . "</title>";
        $ret .= $this->setMetaData();
        $ret .= $this->setCSS();
        $ret .= "</head><body>";
        $ret .= $this->HTML;
        $ret .= $this->ini->JS->makeJs();
        $ret .= $this->formatHtml("</body></html>");
        $ret .= $this->dump($_POST, 'POST');
        $ret .= $this->dump($_GET, 'GET');
        $ret .= $this->dump($_SESSION, 'SESSION');
        $ret .= $this->dump($_SERVER, 'SERVER');
        $ret .= $this->dump($this, 'this');
        return $ret;
    }

    public function dump($array, $name) {
        $ip = gethostbyname('matekok.asuscomm.com');
        $remId = $_SERVER['REMOTE_ADDR'];
        $ret = '';
        if ($_SERVER['REMOTE_ADDR'] == $ip || $_SERVER['REMOTE_ADDR'] == '::1') {

            $print = $array;
            $blacklist = array('HTML');
            foreach ($blacklist as $value) {
                unset($print->$value);
            }

            $ret = "<div style=\"cursor: pointer;\"><b style=\"color: red;\">DUMP - [$name $ip - $remId]]</b><br><pre style=\"  width: 800px;   <!--margin: 0px 0px 10px 0px;--> display: block; background: white; color: black; font-family: Verdana; border: 1px solid #cccccc; padding: 5px; font-size: 10px; line-height: 13px; overflow:auto;\">";
            $ret .= print_r($array, true);
            $ret .= "</pre></div>";
        }
        return $ret;
    }

    private function setMetaData() {
        $ret = '';
        foreach ($this->metaData as $key => $value) {
            $ret .= '<meta ' . $key . '="' . $value . '">
';
        }
        return $ret;
    }

    private function setCSS() {
        $ret = '';
        $pre = $this->CorrectDir();
        foreach ($this->ini->css as $value) {
            $value2 = (substr($value, 0, 3) === "css") ? $pre : '';
            $value2 .= $value;
            $ret .= '<link rel="stylesheet" type="text/css" href="' . $value2 . '" media="all">';
        }
        return $ret;
    }

    public function CurrentMenu($menu) {
        return (in_array($menu, $this->ini->DAO->getSessionValue('page'))) ? ' class="active current"' : '';
    }

    public function CorrectDir() {
        $pre = '';
        if (count($this->ini->DAO->getSessionValue('page')) >= 2)
            $pre = str_repeat("../", count($this->ini->DAO->getSessionValue('page')) - 1);
        return $pre;
    }

    public function setSessionValue($name, $value) {
        if ($value != NULL)
            $_SESSION[$this->ini->engine][$name] = $value;
        else
            unset($_SESSION[$this->ini->engine][$name]);
    }

    public function getSessionValue($name) {
        if (isset($_SESSION[$this->ini->engine][$name]))
            $ret = $_SESSION[$this->ini->engine][$name];
        else
            $ret = NULL;
        return $ret;
    }

    public function formatHtml($html) {
        require_once 'script/php-format-html-output-master/format.php'; //'format.php');
        $format = new Format;
        $ret = $format->HTML($html);
        return $ret;
    }

    public function callFunc() {
        //$this->ini->JS->alert();
        $_GET['page'] = (isset($_GET['page']))?$_GET['page']:array(1=>'index');
        
        $page = $this->ini->DAO->getSessionValue('page');
        if(isset($page[1]) && (in_array($page[1], array('index', 'login')) || $this->ini->DAO->getSessionValue('userId'))){
            $prestring = 'pre_'.$page[1];
            if(method_exists($this, $prestring)){
                $this->$prestring();
            }
        }
        
        $this->setSessionValue('page', $_GET['page']);
        new FramePageCoordinator($this->ini);
        
        $page = $this->ini->DAO->getSessionValue('page');
        if(isset($page[1]) && (in_array($page[1], array('index', 'login')) || $this->ini->DAO->getSessionValue('userId'))){
            $poststring = 'post_'.$page[1];
            if(method_exists($this, $poststring)){
                $this->$poststring();
            }
        }

//        $ret = $template->getPlugins();
//        print_r($ret);
//        foreach ($ret['css'] as $value) {
//            if(!in_array($value, $this->ini->css)) $this->ini->css[] = $value;
//        }
//        foreach ($ret['js'] as $value) {
//            if(!in_array($value, $this->ini->css)) $this->ini->css[] = $value;
//        }
    }
//    private function pre_index(){
//        print 'yeeeeeee!!!!!<br><br><br>';
//    }
//    private function pre_test(){
//        print 'pre_test yeeeeeee!';
//    }
//    private function post_test() {
//        print 'post_test yeeeee!';
//    }
    
}

?>