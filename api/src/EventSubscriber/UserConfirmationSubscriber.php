<?php
/**
 * Created by PhpStorm.
 * User: Tahina
 * Date: 04/12/2019
 * Time: 21:07
 */

namespace App\EventSubscriber;


use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Response;

class UserConfirmationSubscriber implements EventSubscriberInterface
{

    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * UserConfirmationSubscriber constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager
    )
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE]
        ];
    }

    public function confirmUser(GetResponseForControllerResultEvent $event)
    {
        $request = $event->getRequest();

        if ('api_user_confirmations_post_collection' != $request->get('_route')){
            return;
        }

        $confirmationToken = $event->getControllerResult();
        $user = $this->userRepository->findOneBy(
            ['confirmationToken' =>$confirmationToken->confirmationToken]
        );

        // User was NOT found by confirmation token

        if (!$user){
            throw new NotFoundHttpException();
        }

        // User was found confirmation token
        if ($user){
            $user->setEnabled(true);
            $user->setConfirmationToken(null);
            $this->entityManager->flush();
        }

        $event->setResponse(new JsonResponse(null, Response::HTTP_OK));

        // Send e-mail here..
//        $message = (new \Swift_Message('Hello From API PLATFORM!'))
//            ->setFrom('epsi.fenozotahin@gmail.com')
//            ->setTo('960gd7@gmail.com')
//            ->setBody('Hello how are you !');
//
//        $this->mailer->send($message);
    }
}