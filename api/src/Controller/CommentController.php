<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("my_api")
 */
class CommentController extends AbstractController 
{   
    /**
     * @Route("/blog_posts/{id}/comments", name="comments_by_blog")
     */
    public function getCommentList (BlogPost $blogPost= null, Request $request)
    {
        $response = [];

        if ('GET' !== $request->getMethod()) {
            return $this->json(['status' => 'error']);
        }
        $statement = $this->getDoctrine()->getRepository(Comment::class);
        $response = $statement->getList($blogPost);
        return $this->json($response);
    }

    /**
     * @Route("/comment/{id}/delete")
     */
    public function delete($id) {

        $response = [];

        if ('DELETE' === $request->getMethod()) {
            $response = 'action de supprimer';
        }

        return $this->json([$response]);
    }
}

