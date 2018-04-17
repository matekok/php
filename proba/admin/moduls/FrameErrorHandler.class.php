<?php
final class FrameErrorHandler {

  private $ini;
  private $DAO;
  private $hideText = "<br><br><center><b>Hiba az oldalon!</b><br><br>
						<b>Ügyfélszolgálat:</b> CLB Független Biztosítási Alkusz Kft. 1065 Budapest, Bajcsy-Zsilinszky út 21.<br>
						<b>Telefon:</b> 06-1-999-0620 (budapesti szám), <b>Fax:</b> 473-3001, <b>E-mail:</b> web@clb.hu</center>";
  private $wasError;
  private $emailAddress;  // erre a címre küldi a levelet

  function __construct(FrameInit& $ini, $email="engine@clb.hu") {
    $this->wasError = FALSE;
    $this->ini=$ini;
    $this->DAO = new DAO($ini);
    $this->emailAddress=$email;
//    require_once ("/srv/www/vhosts/www.clb.hu/www/dynamic/".$this->ini->priceCalcSubDir."/mailer/Mailer.class.php");
    ini_set("display_errors","On");
    ini_set("error_prepend_string","<error>");
    ini_set("error_append_string","</error>");
    ob_start(array($this, "fatal_error_handler"));
    set_error_handler(array($this, "error_handler"),E_ALL & E_STRICT & ~E_NOTICE);
    set_exception_handler(array($this, "exception_handler"));
  }

  public function error_handler($code, $message, $file, $line) {
    $this->wasError = TRUE;
    throw new ErrorException($message, 0, $code, $file, $line);
  }

  public function fatal_error_handler($buffer) {
    try {
      if($this->ini->showError||$this->ini->priceCalcSubDir=="devel") {
        if (strstr($buffer,"<error>")) {
          $buffer.=Util::dump($_POST, "\$_POST");
          $buffer.=Util::dump($_SESSION[$this->ini->engine], "\$_SESSION[".$this->ini->engine."]");
          if(isset($_SESSION[CST::PRE_DOC.$this->ini->engine])) $buffer.=Util::dump($_SESSION[CST::PRE_DOC.$this->ini->engine], "DOC");
        }
        return $buffer;
      }
      if (strstr($buffer,"<error>")) {
        $this->wasError = TRUE;
        $msg = preg_split("/<\\/?error>/", $buffer, -1, PREG_SPLIT_NO_EMPTY);
        $msg = DAO::escapeMySQLValue($msg[0]);
        $this->saveError(666, "FATAL ERROR", $msg);
        return $this->hideText;
      }else return $buffer;
    }catch (Exception $e) {
      return get_class($e)."<br>ErrorHandler fatal_error_handler metodusan beluli hiba. Error:<br>".$e->getMessage()."<br>ebben a sorban: ".$e->getLine();
    }
  }

  public function exception_handler(Exception $ex) {
    $this->wasError = TRUE;
    try {
      if ($this->ini->showError||$this->ini->priceCalcSubDir=="devel") {
        echo "<br>\n".$ex->__toString()."<br>\n<br><br>\n\n";
        echo Util::dump($_POST, "\$_POST");
        echo Util::dump($_SESSION[$this->ini->engine], "\$_SESSION[".$this->ini->engine."]");
        if(isset($_SESSION[CST::PRE_DOC.$this->ini->engine])) echo Util::dump($_SESSION[CST::PRE_DOC.$this->ini->engine], "DOC");
        return;
      }

      $msg = DAO::escapeMySQLValue($ex->getMessage());
      $this->saveError($ex->getCode(), get_class($ex), $msg);
      echo $this->hideText;
    }catch (Exception $e) {
      echo get_class($e)." ErrorHandler exception_handler metodusan beluli hiba. Error: ".$e->getMessage()." ebben a sorban: ".$e->getLine();
    }
  }

  private function saveError($errorCode,$errorType,$msg) {
    //      if (get_class($ex)!="MysqlException" && !strstr($ex->getMessage(),"mysqli::connect")) {
    $this->smsReport($msg);

    $ip=DAO::escapeMySQLValue($_SERVER["REMOTE_ADDR"]);
    $host=DAO::escapeMySQLValue(gethostbyaddr($ip));
    $agent = DAO::escapeMySQLValue($_SESSION[$this->ini->engine]["agent"]);
    $vars["\$_SESSION"] = $_SESSION;
    $vars["\$_POST"] = $_POST;
    $vars["\$_GET"] = $_GET;
    $vars["\$_FILES"] = $_FILES;
    $vars["\$_SERVER"] = $_SERVER;
    $vars=base64_encode(serialize($vars));

    $this->DAO->query("INSERT INTO log_error (`engine`,`code`,`desc`,`text`,`agent`,`ip`,`host`,`session`) VALUES ('".$this->ini->engine."',".$errorCode.",'".$errorType."','".$msg."','".$agent."','".$ip."','".$host."','".$vars."')");
    if(!class_exists('Mailer'))require_once("/srv/www/vhosts/www.clb.hu/www/dynamic/".$this->ini->priceCalcSubDir."/mailer/Mailer.class.php");
    $mail = new Mailer($this->ini->priceCalcSubDir);

    //$mail->AddAddress("andras.ludanyi@clb.hu");
    $mail->AddAddress($this->emailAddress);

    $body = $errorType." ID:".$this->DAO->insert_id."\n";
    $body .= $this->ini->priceCalcSubDir."\n\n".$msg."\n\n";
    /*$body .= "--------------------\nPOST:\n".print_r($_POST, true)."\n\n";
    $body .= "--------------------\nGET:\n".print_r($_GET, true)."\n\n";
    $body .= "--------------------\nFILES:\n".print_r($_FILES, true)."\n\n";
    $body .= "--------------------\nSERVER:\n".print_r($_SERVER, true)."\n\n";
    $body .= "--------------------\nSESSION:\n".print_r($_SESSION, true)."\n\n";*/

    $mail->sendMail(5,
            array(
            "From" => "engine@clb.hu",
            "FromName" => "Saját Mappa - error",
            "body" => $body
    ));
  }

  public function end() {
    ob_end_flush();
    if($this->wasError) exit(0);
  }


  private function smsReport($msg) {
    $id = -1;

    if(strpos($msg, "couldn\'t connect to host"))
      $id = 1;
    else if(strpos($msg, "mssql_connect(): Unable to connect to server"))
      $id = 2;
    else if(strpos($msg, "mssql_query(): Query failed"))
      $id = 3;

    if($id != -1)
      $this->ini->sendSmsReport($id);
  }

}
?>
