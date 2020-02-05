<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 02/12/2019
 * Time: 21:13
 */

namespace App\Entity;


interface PubishedDateEntityInterface
{
    public function setPublished(\DateTimeInterface $published): PubishedDateEntityInterface;
}