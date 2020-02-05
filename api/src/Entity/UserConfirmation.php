<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 04/12/2019
 * Time: 20:54
 */

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ApiResource(
 *     collectionOperations={
 *         "post"={
 *              "path"="/users/confirm"
 *          }
 *     },
 *     itemOperations={}
 * )
 */
class UserConfirmation
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=30,max=30)
     */
    public $confirmationToken;
}