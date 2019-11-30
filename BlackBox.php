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

        foreach ($arguments as $key => $argument) {
            if (empty($values[$key]) || $get[$argument] == $values[$key]) {
                unset($get[$argument]);
            }
            else {
                $get[$argument] = $values[$key];
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
