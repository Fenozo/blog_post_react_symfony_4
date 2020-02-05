<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 04/12/2019
 * Time: 19:09
 */

namespace App\Email;


use App\Entity\User;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;

    public function __construct(
        \Swift_Mailer $mailer,
        \Twig_Environment $twig
    )
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }

    public function sendConfirmationEmail(User $user)
    {
        $body = $this->twig->render(
            'email/confirmation.html.twig',
            [
                'user' => $user
            ]
        );

        $message = (new \Swift_Message('Hello From API PLATFORM!'))
            ->setFrom('piotr.jura.udemy@gmail.com')
            ->setTo($user->getEmail())
            ->setBody($body);

        $this->mailer->send($message);
    }
}