<?php
class BlackBox {
    /**
     * Generate URL with the requested URL and optional alternate arguments
     *
     *
     *
     * @param mixed $keys Query argument key to modify. Only existing arguments will be affected.
     * @param mixed $values New value of the query argument key. False, empty or identical value will result in removal of query argument.
     * @param array $extra_arguments Additional new arguments to add to the query
     * @return string
     */
    public function url($arguments, $values = [], $extraArguments = []) {
        $arguments = (array) $arguments;
        $values = (array) $values;
        $extraArguments = (array) $extraArguments;

        $requestData = $this->array_map_recursive("urldecode", $_GET) + $extraArguments;;

        foreach ($arguments as $key => $argument) {
            if (isset($requestData[$argument]) && (empty($values[$key]) || $requestData[$argument] == $values[$key])) {
                unset($requestData[$argument]);
            }
            else {
                $requestData[$argument] = $values[$key];
            }
        }

        $url =  $this->is_ssl() ? "https://" : "http://";
        $url .= $_SERVER['HTTP_HOST'];
        $url .= $_SERVER['SCRIPT_NAME'];
        $url .= empty($requestData) ?: '?' . urldecode(http_build_query($requestData));

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
