<?php

namespace utils;

class GeradorCodigoEmail{

    public static function gerar(): string {

        return (string) random_int(100000, 999999);
    }
}