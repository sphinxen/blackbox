<?php
namespace Blackbox;

class BlackBox {

    /**
     * Generate URL with the requested URL and optional alternate arguments
     *
     * Generates a URL-encoded string with alternated query. It is possible to change change or remove existing values
     * from the query buy sending in existing key(s) to search for and replacement value(s). Additional queries could be
     * inserted as an associated array for the third argument.
     *
     * @param mixed $query_keys Query argument key(s) to modify. Could be a string or array of keys. Only existing arguments will be affected.
     * @param mixed $new_values New value of the query argument key. Coulde be a string och array of values. False, empty or identical to existing value will
     * result in removal of query argument.
     * @param array $extra_query_data Additional query to add. An associative array with query name and value.
     * @return string
     */
    public function url($query_keys, $new_values = false, array $extra_query_data = []) {
        $query_keys = (array) $query_keys;
        $new_values = (array) $new_values;
        $query = $_GET;

        foreach ($query_keys as $index => $key) {
            if(isset($query[$key])) {
                if ((empty($new_values[$index]) || $query[$key] == $new_values[$index])) {
                    unset($query[$key]);
                } else {
                    $query[$key] = $new_values[$index];
                }
            }
        }

        return  ($this->is_ssl() ? "https://" : "http://")
            . $_SERVER['HTTP_HOST']
            . $_SERVER['SCRIPT_NAME']
            . ($query ? '?' . http_build_query($query + $extra_query_data) : '');
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
