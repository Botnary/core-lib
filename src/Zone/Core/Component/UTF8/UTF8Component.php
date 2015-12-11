<?php
/**
 * Created by IntelliJ IDEA.
 * User: Prog1
 * E-mail: my.test@live.ca
 * Date: 12/11/2015
 * Time: 10:09 AM
 */

namespace Zone\Core\Component\UTF8;


class UTF8Component
{
    function getLocale()
    {
        return 'en_US';
    }

    protected function mbstring_binary_safe_encoding($reset = false)
    {
        static $encodings = array();
        static $overloaded = null;

        if (is_null($overloaded))
            $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);

        if (false === $overloaded)
            return;

        if (!$reset) {
            $encoding = mb_internal_encoding();
            array_push($encodings, $encoding);
            mb_internal_encoding('ISO-8859-1');
        }

        if ($reset && $encodings) {
            $encoding = array_pop($encodings);
            mb_internal_encoding($encoding);
        }
    }

    protected function reset_mbstring_encoding()
    {
        $this->mbstring_binary_safe_encoding(true);
    }

    function seemsUtf8($str)
    {
        $this->mbstring_binary_safe_encoding();
        $length = strlen($str);
        $this->reset_mbstring_encoding();
        for ($i = 0; $i < $length; $i++) {
            $c = ord($str[$i]);
            if ($c < 0x80) $n = 0; // 0bbbbbbb
            elseif (($c & 0xE0) == 0xC0) $n = 1; // 110bbbbb
            elseif (($c & 0xF0) == 0xE0) $n = 2; // 1110bbbb
            elseif (($c & 0xF8) == 0xF0) $n = 3; // 11110bbb
            elseif (($c & 0xFC) == 0xF8) $n = 4; // 111110bb
            elseif (($c & 0xFE) == 0xFC) $n = 5; // 1111110b
            else return false; // Does not match any model
            for ($j = 0; $j < $n; $j++) { // n bytes matching 10bbbbbb follow ?
                if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
                    return false;
            }
        }
        return true;
    }

    function utf8_uri_encode($utf8_string, $length = 0)
    {
        $unicode = '';
        $values = array();
        $num_octets = 1;
        $unicode_length = 0;

        $this->mbstring_binary_safe_encoding();
        $string_length = strlen($utf8_string);
        $this->reset_mbstring_encoding();

        for ($i = 0; $i < $string_length; $i++) {

            $value = ord($utf8_string[$i]);

            if ($value < 128) {
                if ($length && ($unicode_length >= $length))
                    break;
                $unicode .= chr($value);
                $unicode_length++;
            } else {
                if (count($values) == 0) {
                    if ($value < 224) {
                        $num_octets = 2;
                    } elseif ($value < 240) {
                        $num_octets = 3;
                    } else {
                        $num_octets = 4;
                    }
                }

                $values[] = $value;

                if ($length && ($unicode_length + ($num_octets * 3)) > $length)
                    break;
                if (count($values) == $num_octets) {
                    for ($j = 0; $j < $num_octets; $j++) {
                        $unicode .= '%' . dechex($values[$j]);
                    }

                    $unicode_length += $num_octets * 3;

                    $values = array();
                    $num_octets = 1;
                }
            }
        }

        return $unicode;
    }
}