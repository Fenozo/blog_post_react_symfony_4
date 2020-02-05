<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 * @Route("BlogPost")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog", defaults={"page":5}, requirements={"page"})
     */
    public function list($page, Request $request)
    {
        $limit = $request->get('limit', 10);
        $that = $this;
        $blogs = $that->getDoctrine()->getRepository(BlogPost::class);

        $datas = $blogs->findAll();

        return $this->json(
          [
              'page' => $page,
              'limit' => $limit,
              'urls'=>array_map(function($item) use($that){
                  return $that->generateUrl('blog_by_id', ['id'=>$item->getId()]);
              }, $datas),
              'datas' => array_map(function($item) {

                  return [
                      'id'      => $item->getId(),
                      'title'   => $item->getTitle(),
                      'content' => $item->getContent(),
                      'createdAt' => $item->getCreatedAt()

                  ];
              }, $datas)
          ], 200);

    }

    /**
     * @Route("/blog/{id}", name="blog_by_id")
     */
    public function blog_by_id($id)
    {
        $blog = $this->getDoctrine()->getRepository(BlogPost::class);
        return new JsonResponse(200, $blog->findBy([
            'id' => $id
        ]));
    }

    /**
     * @param $slug
     * @return JsonResponse
     * @Route("/blog/{slug}", name="blog_by_slug")
     */
    public function blog_by_slug($slug)
    {
        return $this->json(
          $this->getDoctrine()->getRepository(BlogPost::class)->findBy([
              "slug" => $slug,
          ])
        );
    }
    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        $serializer = $this->get("serializer");

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
            $blogPost->setCreatedAt(null);
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost, 200);
    }

}
