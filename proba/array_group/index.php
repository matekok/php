<?php

function connectDb() {
    global $dbh;
    $host = 'localhost';
    $db = 'thesportidea';
    $user = 'root';
    $pass = '';
    $charset = 'utf8';
    $dbh = new PDO('mysql:host=' . $host . ';dbname=' . $db . ';charset=' . $charset, $user, $pass);
}

function getArr() {
    global $dbh;
    //build the query
    $query = "SELECT * FROM listofsport";
    //execute the query
    $data = $dbh->query($query);
    //convert result resource to array
    return $data->fetchAll(PDO::FETCH_ASSOC);
}

function buildTree($flat, $pidKey, $idKey = null) {
    $grouped = array();
    foreach ($flat as $sub) {
        $grouped[$sub[$pidKey]][] = $sub;
    }
    $fnBuilder = function($siblings) use (&$fnBuilder, $grouped, $idKey) {
        foreach ($siblings as $k => $sibling) {
            $id = $sibling[$idKey];
            if (isset($grouped[$id])) {
                $sibling['children'] = $fnBuilder($grouped[$id]);
            }
            $siblings[$k] = $sibling;
        }
        return $siblings;
    };
    $tree = $fnBuilder($grouped[0]);
    return $tree;
}

function print_tree($array, $level = 0) {
    $html = '';
    foreach ($array as $child) {
        //print '<pre>'.print_r($child, true).'</pre>';
        $html .= '<option value="' . $child['id'] . '">';
        $html .= str_repeat("&nbsp&nbsp&nbsp&nbsp&nbsp", $level);
        $html .= $child['name'] . ' - [' . $level . ']';
        $html .= "</option>";
        if (!empty($child['children']))
            $html .= print_tree($child['children'], $level + 1);
    }
    return $html;
}

header('Content-Type: text/html; charset=UTF-8');
$dbh = null;
connectDb();
$as = getArr();
$tree = buildTree($as, 'parent', 'id');
?>
<select multiple>
<?php
print print_tree($tree);
?>
</select>