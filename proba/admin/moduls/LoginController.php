<?php
class LoginController {
    protected $HTML;
    public $ini;
    public function __construct($ini = NULL) {
        $this->ini=$ini;
    }
    public function indexAction() {
        $Test = new Test($this);
        var_dump($Test->get());
        var_dump($Test->text);
    }

    public function login() {
        $test = new Test($this);
        if (!empty($_SESSION['user_id'])) {
            $ret = $test->userData();
        } else {
            $ret = $test->userLogin();
            $this->HTML .= '<div id=\"login\">
                        <div id=\"login_user_desc\">#lang#user_name#vlang#</div><div id=\"login_user_imp\"><input type="text" name="user"></div>
                        <div id=\"login_pass_desc\">#lang#pass#vlang#</div><div id=\"login_pass_imp\"><input type="password" name="pass"></div>
                        </div>';
        }
        
        $this->HTML .= $ret;
    }
    public function getHTML(){
        return $this->HTML;
    }

}

?>
