<?php

$test = 1;
if (!$test)
    error_reporting(0);
set_time_limit(60);

$dataId = $_GET['id'];
$proxy = '50.233.137.36:80';
$table = 'sportevents';
$dbh = null;
$html = null;

if($dataId>=2000) exit();

$html = getHtml();
print 'chars: ' . strlen($html) . '<br>'.$dataId.'<br>';

if (strlen($html) > 14000) {
    //if access data
    $dataStoll = getData();
    $dataPost = array(
        'id' => $dataId,
        'name' => 'test',
        'htmlC' => strlen($html),
        'location' => $dataStoll['Venue'],
        'organizator' => $dataStoll['Organization'],
        'contact' => $dataStoll['Contact'],
        'phone' => $dataStoll['Phone'],
        'fax' => $dataStoll['Fax'],
        'email' => $dataStoll['E-Mail'],
        'homepage' => $dataStoll['Homepage'],
        'datetime' => date('Y-m-d H:i:s')
    );

    $dataPost = array_merge($dataPost, $dataStoll);

    connectDb();
    $dataBack = insertRow($table, $dataPost);
    if ($test) {
        print '<pre>';
        print_r($dataPost);
        print_r($dataBack);
        print '</pre>';
    }
    $dataId++;
}
else if(strlen($html) > 1000){
    //if data is lost
    $dataId++;
}

echo '<script>window.location.href = "index.php?id='.$dataId.'";</script>';

function connectDb() {
    global $dbh;
    $host = 'localhost';
    $db = 'thesportidea';
    $user = 'root';
    $pass = '';
    $charset = 'utf8';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $dbh = new PDO($dsn, $user, $pass, $opt);
    //return $pdo;
}

function insertRow($table = NULL, $values = array(), $fields = array(), $prie = array()) {
    global $dbh;
    global $test;
    $return = array('success' => 1, 'error' => array());
    /*
     *  require table name and values
     */
    if (is_null($table)) {
        $return['success'] = 0;
        $return['error'][] = 'table name (NULL)';
        return $return;
    }
    if (empty($values)) {
        $return['success'] = 0;
        $return['error'][] = 'values (EMPTY)';
    }
    /*
     *  field and prie empty
     */
    if (empty($field) && empty($prie)) {
        $q = $dbh->prepare("SHOW COLUMNS FROM " . $table);
        $q->execute();
        $table_fields = $q->fetchAll();

        foreach ($table_fields as $key => $value) {
            if (!in_array($value['Field'], $fields))
                $fields[] = $value['Field'];
            if ($value['Key'] == 'PRI') {
                if (!in_array($value['Field'], $prie))
                    $prie[] = $value['Field'];
                if (!isset($values[$value['Field']])) {
                    $return['success'] = 0;
                    $return['error'][] = $value['Field'] . ' (NULL) but it is PRIMARY';
                }
            }
        }
    }

    /*
     * set where
     */
    $updateW = '';
    foreach ($prie as $key => $value) {
        $updateW .= '`' . $value . '` = :where_' . $value . '_ & ';
    }
    if (strlen($updateW) > 0) {
        $queryWhere = ' WHERE ' . substr($updateW, 0, -2);
    }

    /*
     * select prewious row
     */
    $query = "SELECT * FROM " . $table . $queryWhere;
    if ($test)
        print $query . '<br>';
    $stmt = $dbh->prepare($query);
    foreach ($prie as $key => $value) {
        $stmt->bindParam(':where_' . $value . '_', $values[$value]);
        if ($test)
            print ':where_' . $value . '_, ' . $values[$value] . '<br>';
    }
    $stmt->execute();
    $data = $stmt->fetchAll();

    /*
     * unset unused fields 
     */
    foreach ($fields as $key => $value) {
        if (!isset($values[$value]))
            unset($fields[$key]);
    }

    /*
     * generate sql string
     */
    if (count($data) == 0) {
        $query = "INSERT INTO " . $table;
        /*
         * set values
         */
        $insertK = !empty($fields) ? implode(",", $fields) : '';
        $insertV = !empty($fields) ? ':' . implode(",:", $fields) : '';

        if (strlen($insertK) > 0 && strlen($insertV) > 0) {
            $query .= '(' . $insertK . ') VALUES (' . $insertV . ')';
        }
    } else {
        $query = "UPDATE " . $table;
        $updateS = '';
        /*
         * set values string        
         */
        foreach ($fields as $key => $value) {
            $updateS .= '`' . $value . '` = :' . $value . ', ';
        }

        if (strlen($updateS) > 0) {
            $query .= ' SET ' . substr($updateS, 0, -2);
        }
        $query .= $queryWhere;
    }
    if ($test)
        print $query . '<br>';

    /*
     * set data to UPDATE/INSERT
     */
    $stmt = $dbh->prepare($query);
    foreach ($fields as $key => $value) {
        $stmt->bindParam(':' . $value, $values[$value]);
        if ($test)
            print ':' . $value . ', ' . $values[$value] . '<br>';
    }
    if (substr($query, 0, 6) != 'INSERT') {
        foreach ($prie as $key => $value) {
            $stmt->bindParam(':where_' . $value . '_', $values[$value]);
            if ($test)
                print ':where_' . $value . '_, ' . $values[$value] . '<br>';
        }
    }
    /*
     * execute and close       
     */
    $stmt->execute();
    /*
     * get the new data
     */
    if ($return['success'] == 1) {
        $query = "SELECT * FROM " . $table . $queryWhere;
        if ($test)
            print $query . '<br>';
        $stmt = $dbh->prepare($query);
        foreach ($prie as $key => $value) {
            $stmt->bindParam(':where_' . $value . '_', $values[$value]);
            if ($test)
                print ':where_' . $value . '_, ' . $values[$value] . '<br>';
        }
        $stmt->execute();
        $data = $stmt->fetchAll();
        $return['data'] = $data;
    }
    /*
     * return with data
     */
    return $return;
}

function getHtml() {
    global $proxy;
    global $dataId;
    $url = '';
    $aContext = array(
             'http' => array(
              'proxy' => 'tcp://' . $proxy,
              'request_fulluri' => true,
              ), 
    );
    $cxContext = stream_context_create($aContext);
    $html = file_get_contents($url, False, $cxContext);
    return $html;
}

function getData() {
    global $html;
    $dom = new domDocument;
    /*
     * load the html into the object
     */
    $dom->loadHTML($html);
    /*
     * discard white space
     */
    $dom->preserveWhiteSpace = false;

    /*
     * the table by its tag name
     */
    $tables = $dom->getElementsByTagName('h1');
    /*
     * get all rows from the table
     */
    $name = $tables->item(2)->nodeValue;
    $dateils = array();
    $dateils['name'] = $name;
    /*
     * the table by its tag name
     */
    $tables = $dom->getElementsByTagName('table');
    /*
     * get all rows from the table
     */
    $rows = $tables->item(0)->getElementsByTagName('tr');
    /*
     * loop over the table rows
     */
    foreach ($rows as $row) {
        $cols = $row->getElementsByTagName('td');
        $dateils[$cols->item(0)->nodeValue] = trim(preg_replace('/\s+/', ' ', preg_replace("/<img[^>]+\>/i", "", $cols->item(1)->nodeValue)));
    }
    return $dateils;
}
?>