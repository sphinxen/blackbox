<?php
class BlackBox {
    public function url($var, $val = false, $add_var = false) {
        $get = $_GET;
        $get = $this->array_map_recursive("urldecode", $get);
        if ($add_var) {
            $get = $get + $add_var;
        }

        $http = $this->is_ssl() ? "https://" : "http://";

        if (is_array($var)) {
            foreach ($var as $i => $v) {
                if (isset($get[$v])) {
                    if ($val == false || $get[$v] == $val[$i] || $val[$i] == "") {
                        unset($get[$v]);
                    }
                    else {
                        $get[$v] = $val[$i];
                    }
                }
                else {
                    if ($val == false) {

                    }
                    else {
                        $get = $get + array($v => $val[$i]);
                    }
                }
            }
        }
        else {
            if (isset($get[$var])) {
                if ($get[$var] == $val || $val == "" || $val == false) {
                    unset($get[$var]);
                }
                else {
                    $get[$var] = $val;
                }
            }
            else {
                if ($val == false) {

                }
                else {
                    $get = $get + array($var => $val);
                }
            }
        }
        return $http . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'] . "?" . urldecode(http_build_query($get));

    }


    private function array_map_recursive($callback, $array) {
        foreach ($array as $key => $value) {
            if (is_array($array[$key])) {
                $array[$key] = $this->array_map_recursive($callback, $array[$key]);
            } else {
                $array[$key] = call_user_func($callback, $array[$key]);
            }
        }
        return $array;
    }


    private function is_ssl() {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS'])) {
                return true;
            }

            if ('1' == $_SERVER['HTTPS']) {
                return true;
            }
        }
        else if (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }

}
?>
