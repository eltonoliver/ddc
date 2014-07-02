<?php
////////////////////////////////////////////////////////////////////////////////////////////////////
////    File:
////        mysql.php
////    Actions:
////        1) connect to mysql database
////        2) select a mysql database
////        3) compile mysql queries
////        4) perform mysql queries
////        5) error handle miscommunication with database
////    Account:
////        Added on March 23rd 2006 for ternstyle (tm) v1.0.0
////        Restructured on January 8th 2008 for ternstyle (tm) v3.0.0
////    Version:
////        3.8
////
////    Written by Matthew Praetzel. Copyright (c) 2006 Matthew Praetzel.
////    http://www.ternstyle.us/products/php-classes/mysql-php-class
////    http://wiki.ternstyle.us/index.php/MySQL_PHP_Class_Object
////////////////////////////////////////////////////////////////////////////////////////////////////

/****************************************Commence Script*******************************************/

class mysqlClass {

    function __construct($h,$u,$p,$d) {
        $this->db = $d;
        if($this->c = @mysql_connect($h,$u,$p,true)) {
            return $this->selectdb($d);
        }
        $this->errors();
        return false;
    }
    function selectdb($d) {
        if(@mysql_select_db($d,$this->c)) {
            # Aqui está o segredo
            mysql_query("SET NAMES 'utf8'");
            mysql_query('SET character_set_connection=utf8');
            mysql_query('SET character_set_client=utf8');
            mysql_query('SET character_set_results=utf8');              
            return true;
        }
        $this->errors();
        return false;
    }
    function query($q,$t=true,$a=false) {
        $q = $this->records($q,$t);
        if(!$q and $a) {
            return array();
        }
        return $q;
    }
    function records($q,$t) {
        $m = array();
        if($r = @mysql_query($q,$this->c)) {
            while($f = @mysql_fetch_assoc($r)) {
                if(!$t) {
                    return $f;
                }
                $m[] = $f;
            }
        }
        $this->errors();
        @mysql_free_result($f);
        return $m;
    }
    function update($q) {
        if(@mysql_query($q,$this->c)) {
            return true;
        }
        $this->errors();
        return false;
    }
    function errors() {
        global $mysql_errors;
        $mysql_errors[mysql_errno($this->c)] = mysql_error($this->c);
    }
    function disconnect() {
        if(@mysql_close($this->c)) {
            return true;
        }
        return false;
    }
    function insertQuery($a,$t) {
        foreach($a as $k => $v) {
            $v = $this->clean($v);
            $s .= empty($s) ? $k : "," . $k;
            $w .= empty($w) ? "" . $v . "" : "," . $v . "";
        }
        return "insert into " . $t . " (" . $s . ") values (" . $w . ")";
    }
    function updateQuery($a,$t,$w) {
        foreach($a as $k => $v) {
            $v = $this->clean($v);
            $u .= empty($u) ? $k . "=" . $v . "" : "," . $k . "=" . $v . "";
        }
        return "update " . $t . " set " . $u . " where " . $w;
    }
    function select($a,$t) {
        $b = array(
            'excludes'  => array(),
            'limit'     => NULL,
            'orderby'   => NULL,
            'order'     => 'asc',
            'offset'    => 0
        );
        $a = array_merge($b,$a);
        $o = array('limit','orderby','order','offset');
        foreach($a as $k => $v) {
            if($v !== NULL and !in_array($k,$o)) {
                if(strpos($v,':') !== false) {
                    $m = explode('&',$v);
                    foreach($m as $y) {
                        $p = explode(':',$y);
                        $x = $p[1];$p = $p[0];
                        if($p == 'contains') {
                            $w .= " and instr($t.".$k.",".$this->clean($x).") != 0 ";
                        }
                        elseif($p == 'no') {
                            $w .= " and instr($t.".$k.",".$this->clean($x).") = 0 ";
                        }
                        elseif($p == 'greater') {
                            $w .= ' and '.$t.'.'.$k.' > '.$x.' ';
                        }
                        elseif($p == 'lesser') {
                            $w .= ' and '.$t.'.'.$k.' < '.$x.' ';
                        }
                        elseif($p == 'range') {
                            $x = explode('-',$x);
                            $w .= ' and '.$t.'.'.$k.' > '.$x[0]." and $t.".$k.' < '.$x[1].' ';
                        }
                    }
                }
                elseif($k == 'excludes') {
                    foreach($v as $z => $x) {
                        foreach($x as $y) {
                            $w .= ' and '.$z.' != '.$this->clean($y).' ';
                        }
                    }
                }
                else {
                    $w .= ' and '.$t.'.'.$k."=".$this->clean($v)." ";
                }
            }
        }
        $l = !empty($a['limit']) ? ' limit '.$a['limit'] : '';
        $l .= !empty($a['offset']) ? ' offset '.$a['offset'] : '';
        $o = !empty($a['orderby']) ? ' order by '.$a['orderby'] : '';
        $o .= !empty($o) ? ' '.$a['order'].' ' : '';
        return $this->query('select * from '.$t.' where 1=1 '.$w.$o.$l);
    }
    function clean($s) {
        /*
        $s = stripslashes($s);
        if(@mysql_real_escape_string($s)) {
            return mysql_real_escape_string($s);
        }
        $s = str_replace('\"','"',$s);
        return $s;
        */
        $value = $s;
        if (is_int($value)) {
            return $value;
        } elseif (is_float($value)) {
            return sprintf('%F', $value);
        }
        return "'" . addcslashes($value, "\000\n\r\\'\"\032") . "'";
    }
}

/****************************************Terminate Script******************************************/
?>