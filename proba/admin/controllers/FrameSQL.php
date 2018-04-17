<?php

class FrameSQL {

    public $ini;
    private $results;
    private $error;
    
    public function __construct($ini = NULL) {
        $this->ini = $ini;
        $this->error[] = 'test_hívás';
    }

    function go_mysql($query) {
        global $mysql_link;

        if (!$mysql_link) {
            $mysql_link = mysql_connect("localhost", "root", "mypass") or die($this->error[]=mysql_error());
            mysql_select_db("my_db") or die($this->error[]=mysql_error());
            mysql_query("SET NAMES 'utf8'");
            mysql_query("set character_set_client='utf8'");
            mysql_query("set character_set_results='utf8'");
            mysql_query("set collation_connection='utf8'");
            global $mysql_link;
        }

        $result = mysql_query($query);
        if ($result) {
            return $result;
        } else {
            $this->error[] = "Database Error: " . mysql_error() . "<br><b>$query</b>";
            die();
        }
        mysql_close();
    }

}

?>