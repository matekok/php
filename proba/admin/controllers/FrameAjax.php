<?php
session_id($_POST['sess_id']);
session_start();
if (!(isset($_GET["ajaxupload"]) && ($_GET["ajaxupload"] == 1)))
    header("Content-Type: application/json");
include __DIR__ . '/../config/ClassAutoLoader.php';
$ClassAutoLoader = new ClassAutoLoader();
$ClassAutoLoader->register();
$ClassAutoLoader->doIni();
class FrameAjax {
    /** @var FrameInit */
    protected $ini;
    /** @var DAO */
    protected $DAO;
    public function __construct($ini) {
        $this->ini = $ini;
        $this->DAO = new FrameDAO($this->ini);
    }
    final public function doit() {
        if (isset($_GET["ajaxupload"]) && ($_GET["ajaxupload"] == 1)) {
            echo $this->enc($this->ajax_upload());
        } else {
            $action = $_POST["method"];
            if (method_exists($this, $action))
                echo $this->{$action}();
        }
    }
    private function location() {
        $this->DAO->setSessionValue('lat', $_POST['lat']);
        $this->DAO->setSessionValue('lon', $_POST['lon']);
        //mail('matekok@gmail.com', 'frameAjax - location', print_r($_SESSION, TRUE));
    }
}
$ajax = new FrameAjax($ClassAutoLoader);
$ajax->doit();
?>