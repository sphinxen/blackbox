<?php
class BlackBox {
    public function url($var, $val = false, $add_var = false) {
        $get = $this->array_map_recursive("urldecode", $_GET);

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

    /**
     * @param $callable
     * @param $array
     * @return array|mixed
     */
    private function array_map_recursive($callable, $array) {
        if (is_array($array)) {
            return array_map(function($array) use ($callable) {
                return $this->array_map_recursive($callable, $array);
            }, $array);
        }
        return call_user_func($callable, $array);
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
