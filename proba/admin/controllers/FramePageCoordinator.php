<?php

class FramePageCoordinator {

    public $ini;

    public function __construct($ini) {
        $this->ini = $ini;
        $this->GeneratePageData();
        $content = $this->ini->DAO->getSessionValue('CONTENT');
        if (empty($content) || !isset($content)) {
            $this->GeneratePageData();
        }
        $this->getPage();
    }

    private function getPage() {
        $template = new template($this->ini);
        $template->getJs();
        $urlArr = array();
        $page = $this->ini->DAO->getSessionValue('page');
        $url = implode("/", $page);
        $urlArr[] = $url;
        $content = $this->ini->DAO->getSessionValue('content');
        if (!isset($content[$url])){
            $url = 'welcome';
            $urlArr[] = $url;
        }
        if (!$this->ini->DAO->getSessionValue('userId')){
            $url = 'login';
            $urlArr[] = $url;
        }
        $this->ini->DAO->setSessionValue('url', $urlArr);
        foreach ($content[$url] as $value) {
            $modul = $value['modul'];
            $this->ini->DAO->HTML .= $template->$modul($value);
        }
    }

    private function GeneratePageData() {
        $pagedata = array();

        $pagedata['welcome'] = array();
        $pagedata['welcome'][] = array('modul' => 'header');
        //$pagedata['welcome'][] = array('modul' => 'LoginInner');
        $pagedata['welcome'][] = array('modul' => 'footer');

        $pagedata['login'] = array();
        $pagedata['login'][] = array('modul' => 'header');
        $pagedata['login'][] = array('modul' => 'LoginInner');
        $pagedata['login'][] = array('modul' => 'footer');
        
        $this->ini->DAO->setSessionValue('content', $pagedata);
    }

}
?>
        