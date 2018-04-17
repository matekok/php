<?php

class Test {

    public $text;
    public $acess;
    public $ini;
    public function _constuct($ini) {
        $this->ini = $ini;  
    }
    
    public function get() {
        $this->text = $_SESSION['test'];
        return 'test';
    }

    public function userData() {
        $_SESSION['user_id'] = false;
        return '#lang#userData#vlang#';
        
    }

    public function userLogin() {
        $_SESSION['user_id'] = true;
        return '#lang#userlogin#vlang#';
        
    }

}

?>