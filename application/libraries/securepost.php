<?php

class securepost {

    function postMethod() {
        $hasil = array();
        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                $hasil[$key] = mysql_real_escape_string($value);
            }
        }
        return $hasil;
    }

    public function test() {
        return 'test';
    }

    function getMethod() {
        $hasil = array();
        if (isset($_GET)) {
            foreach ($_GET as $key => $value) {
                $_GET[$key] = mysql_real_escape_string($value);
            }
        }
    }

    function requestMethod() {
        $hasil = array();
        if (isset($_REQUEST)) {
            foreach ($_REQUEST as $key => $value) {
                $hasil[$key] = mysql_real_escape_string($value);
            }
        }
        return $hasil;
    }

}