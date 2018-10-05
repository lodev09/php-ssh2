<?php

namespace SSH2;

class SFTP extends SSH2 {
    protected $sftp;

    public function __construct($host, Authentication $auth, $port = 22) {
        parent::__construct($host, $auth, $port);
        if ($this->is_connected()) {
            $this->sftp = ssh2_sftp($this->conn);
        } else {
            trigger_error('SFTP Not Connected to host');
        }

    }
    public function __call($func, $args) {
        if (!$this->is_connected()) return false;
        $func = 'ssh2_sftp_' . $func;
        if (function_exists($func)) {
            array_unshift($args, $this->sftp);
            return call_user_func_array($func, $args);
        } else {
            trigger_error($func . ' is not a valid SFTP function');
        }
    }

    public function list($addr, $limit = 0) {
        if (!$this->is_connected()) return false;
        $result = array();
        $files = scandir('ssh2.sftp://'.$this->sftp.'/'.$addr);
        if (!empty($files)) {
            $i = 0;
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    if ($limit && $i >= $limit) break;
                    $result[] = $addr.'/'.$file;
                    $i++;
                }
            }
        }
        return $result;
    }

    public function mv($file, $dest_file, $rename_exists = true) {
        if (!$this->is_connected()) return false;

        if ($rename_exists) {
            $path_info = pathinfo($dest_file);

            $original_name = $path_info['filename'];
            $target_name = $original_name;
            $extension = $path_info['extension'];
            $dirname = $path_info['dirname'];

            $i = 1;
            while($this->exists($dest_file)) {
                $target_name = $original_name.'('.$i.')';
                $dest_file = $dirname.'/'.$target_name.'.'.$extension;
                $i++;
            }
        }

        return $this->rename($file, $dest_file);
    }

    public function rm($remote_file) {
        return unlink('ssh2.sftp://'. $this->sftp.'/'.$remote_file);
    }

    public function get($remote_file, $local_file) {
        if (!$this->is_connected()) return false;
        //ssh2_scp_recv($this->conn, $file, $dest);
        $data = file_get_contents('ssh2.sftp://'. $this->sftp.'/'.$remote_file);
        return file_put_contents($local_file, $data);
    }

    public function put($local_file, $remote_file, $rename_exists = true) {
        if (!$this->is_connected()) return false;

        if ($rename_exists) {
            $path_info = pathinfo($remote_file);

            $original_name = $path_info['filename'];
            $target_name = $original_name;
            $extension = $path_info['extension'];
            $dirname = $path_info['dirname'];

            $i = 1;
            while($this->exists($remote_file)) {
                $target_name = $original_name.'('.$i.')';
                $remote_file = $dirname.'/'.$target_name.'.'.$extension;
                $i++;
            }
        }

        if ($stream = fopen('ssh2.sftp://'.$this->sftp.'/'.$remote_file, 'w')) {
            $data = file_get_contents($local_file);

            if (fwrite($stream, $data)) {
                fclose($stream);
                return true;

            } else return false;
        } return false;
    }

    public function is_dir($remote_file) {
        return is_dir('ssh2.sftp://'.$this->sftp.'/'.$remote_file);
    }

    public function exists($remote_file) {
        return file_exists('ssh2.sftp://'.$this->sftp.'/'.$remote_file);
    }
}