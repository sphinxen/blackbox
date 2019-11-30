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
    public function url($arguments, $values = [], $extraArguments = []) {
        $arguments = (array) $arguments;
        $values = (array) $values;
        $extraArguments = (array) $extraArguments;

        $get = $this->array_map_recursive("urldecode", $_GET) + $extraArguments;;

        $http = $this->is_ssl() ? "https://" : "http://";

        foreach ($arguments as $key => $argument) {
            if (empty($values[$key]) || $get[$argument] == $values[$key]) {
                unset($get[$argument]);
            }
            else {
                $get[$argument] = $values[$key];
            }
        }

        $url =  $this->is_ssl() ? "https://" : "http://";
        $url .= $_SERVER['HTTP_HOST'];
        $url .= $_SERVER['SCRIPT_NAME'];
        $url .= empty($get) ?: '?' . urldecode(http_build_query($get));

        return $url;
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


    /**
     * @return bool
     */
    private function is_ssl() {
        if (isset($_SERVER['HTTPS']) && filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN))
            return true;

        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443')
            return true;

        return false;
    }

}
?>
