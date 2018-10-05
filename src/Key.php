<?php

namespace SSH2;

class Key extends Authentication {
    protected $username;
    protected $publicKey;
    protected $privateKey;

    public function __construct($username, $publicKey, $privateKey) {
        $this->username = $username;
        $this->password = $password;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getPublicKey() {
        return $this->publicKey;
    }

    public function getPrivateKey() {
        return $this->privateKey;
    }
}