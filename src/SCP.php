<?php

namespace SSH2;

class SCP extends SSH2 {
    public function __call($func, $args) {
        if (!$this->is_connected()) return false;
        $func = 'ssh2_scp_' . $func;
        if (function_exists($func)) {
            array_unshift($args, $this->conn);
            return call_user_func_array($func, $args);
        } else {
            trigger_error($func . ' is not a valid SCP function');
        }
    }
}