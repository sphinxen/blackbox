<?php
class BlackBox {
    /**
     * Get the requested URL and update the query arguments
     *
     *
     *
     * @param array|mixed $arguments URL argument key
     * @param array|mixed $values URL argument value
     * @param array|mixed $additionalArguments Additional arguments to add to the URL
     * @return string
     */
    public function url($arguments, $values = false, $extraArguments = false) {
        $get = $this->array_map_recursive("urldecode", $_GET);

        if ($extraArguments) {
            $get = $get + $extraArguments;
        }

        $http = $this->is_ssl() ? "https://" : "http://";

        if (is_array($arguments)) {
            foreach ($arguments as $i => $v) {
                if (isset($get[$v])) {
                    if ($values == false || $get[$v] == $values[$i] || $values[$i] == "") {
                        unset($get[$v]);
                    }
                    else {
                        $get[$v] = $values[$i];
                    }
                }
                else {
                    if ($values == false) {

                    }
                    else {
                        $get = $get + array($v => $values[$i]);
                    }
                }
            }
        }
        else {
            if (isset($get[$arguments])) {
                if ($get[$arguments] == $values || $values == "" || $values == false) {
                    unset($get[$arguments]);
                }
                else {
                    $get[$arguments] = $values;
                }
            }
            else {
                if ($values == false) {

                }
                else {
                    $get = $get + array($arguments => $values);
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
