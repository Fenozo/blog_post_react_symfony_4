<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Routing\RouterInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    private $router;

    public function __construct(ManagerRegistry $registry, RouterInterface $router)
    {
        parent::__construct($registry, Comment::class);
        $this->router = $router;
    }

    // /**
    //  * @return Comment[] Returns an array of Comment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    public function getList($post) 
    {
        $id = "/my_api/comment/list/";
        $qb = $this->createQueryBuilder('c');
       
        if ( $post instanceof BlogPost) {
            $id = $this->router->generate('comments_by_blog', ['id' => $post->getId()]);
            $qb->andWhere('c.blogPost = :post');
            $qb->setParameter('post', $post->getId());
        }

        $qb->orderBy('c.id', 'ASC');
        $commentList = $qb->getQuery()->getResult();
        $data = [];
        $data = [
                    "@id" => $id,
                ];
        array_walk($commentList, function($item, $key) use (&$data){
            $data["hydra:member"][] = [
                '@type'     => "Comment",
                'id'        => $item->getId(),
                'content'   =>  $item->getContent(),
                'published' => $item->getPublished(),
                'author' => [
                        'id'        => $item->getAuthor()->getId(),
                        'username'  => $item->getAuthor()->getUsername(),
                        'name'      => $item->getAuthor()->getName(),
                    ]

            ];
        });

        return $data;
    }

    /*
    public function findOneBySomeField($value): ?Comment
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
