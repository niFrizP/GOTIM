<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RutValido implements Rule
{
    public function passes($attribute, $value)
    {
        // Limpia puntos y guion
        $rut = preg_replace('/[^0-9kK]/', '', $value);
        if (strlen($rut) < 2)
            return false;

        $cuerpo = substr($rut, 0, -1);
        $dv = strtoupper(substr($rut, -1));
        $suma = 0;
        $multiplo = 2;

        for ($i = strlen($cuerpo) - 1; $i >= 0; $i--) {
            $suma += intval($cuerpo[$i]) * $multiplo;
            $multiplo = $multiplo < 7 ? $multiplo + 1 : 2;
        }
        $dvEsperado = 11 - ($suma % 11);
        $dvEsperado = $dvEsperado == 11 ? '0' : ($dvEsperado == 10 ? 'K' : strval($dvEsperado));
        return $dv == $dvEsperado;
    }

    public function message()
    {
        return 'El RUT ingresado no es válido.';
    }
}
