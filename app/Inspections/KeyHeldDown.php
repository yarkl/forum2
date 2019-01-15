<?php
/**
 * Created by PhpStorm.
 * User: yaro
 * Date: 15.01.19
 * Time: 9:26
 */

namespace App\Inspections;


class KeyHeldDown
{
    public function detect($body)
    {
        if (preg_match('/(.)\\1{4,}/', $body)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}