<?php

namespace App\Utils;

class Validate {
    public function isEmpty($value) {
        return $value === '';
    }

    public function ValidateCpf($data) {
         if (strlen($data['nuCpf']) != 11) {
            return "CPF Inválido";
        }    
        return "true";
    }
}