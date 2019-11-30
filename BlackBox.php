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
    public function url($keys, $values = false, array $extra_arguments = []) {
        $keys = (array) $keys;
        $values = (array) $values ?: [];
        $query = (array) $_GET ?: [];

        $query = array_combine(
            $this->array_map_recursive("urldecode", array_keys($query)),
            $this->array_map_recursive("urldecode", $query)
        );

        foreach ($keys as $index => $key) {
            if(isset($query[$key])) {
                if ((empty($values[$index]) || $query[$key] == $values[$index])) {
                    unset($query[$key]);
                } else {
                    $query[$key] = $values[$index];
                }
            }
        }

        $url =  $this->is_ssl() ? "https://" : "http://";
        $url .= $_SERVER['HTTP_HOST'];
        $url .= $_SERVER['SCRIPT_NAME'];
        $url .= $query ? '?' . http_build_query($query + $extra_arguments) : '';

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
