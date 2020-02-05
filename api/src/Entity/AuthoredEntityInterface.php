<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 02/12/2019
 * Time: 21:04
 */

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

interface AuthoredEntityInterface
{
    public function setAuthor(UserInterface $user): AuthoredEntityInterface;
}