<?php

/**
 * @package SSH2 Class in PHP
 * @author Jovanni Lo, Jamie Munro
 * @link https://github.com/lodev09/php-ssh2, http://www.sitepoint.com/using-ssh-and-sftp-with-php/
 * @copyright 2018
 * @license
 * The MIT License (MIT)
 * Copyright (c) 2017 Jovanni Lo
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace SSH2;

class SSH2 {
    protected $conn;
    protected $authentication = false;

    public function __construct($host, SSH2Authentication $auth, $port = 22) {
        $this->conn = ssh2_connect($host, $port);
        if ($this->is_connected()) {
            switch (get_class($auth)) {
                case 'SSH2Password':
                    $username = $auth->getUsername();
                    $password = $auth->getPassword();
                    $this->authentication = ssh2_auth_password($this->conn, $username, $password);

                    if ($this->authentication === false) {
                        trigger_error('SSH2 login is invalid');
                    }
                    break;

                case 'SSH2Key':
                    $username = $auth->getUsername();
                    $publicKey = $auth->getPublicKey();
                    $privateKey = $auth->getPrivateKey();

                    $this->authentication = ssh2_auth_pubkey_file($this->conn, $username, $publicKey, $privateKey);
                    if ($this->authentication === false) {
                        trigger_error('SSH2 login is invalid');
                    }
                    break;

                default:
                    trigger_error('Unknown SSH2 login type');
            }
        }
    }

    public function is_connected() {
        return $this->conn ? true : false;
    }

    public function is_authenticated() {
        return $this->authentication ? true : false;
    }
}
?>