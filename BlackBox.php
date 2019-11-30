<?php
class BlackBox {
    /**
     * Generate URL with the requested URL and optional alternate arguments
     *
     * Generates a URL-encoded string with alternated query. It is possible to change and/or remove existing values from
     * the query.
     *
     * @param mixed $keys Query argument key to modify. Only existing arguments will be affected.
     * @param mixed $values New value of the query argument key. False, empty or identical value will result in removal of query argument.
     * @param array $extra_arguments Additional new arguments to add to the query
     * @return string
     */
    public function url($keys, $values = false, array $extra_arguments = []) {
        $keys = (array) $keys;
        $values = (array) $values ?: [];
        $query = $_GET;

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
