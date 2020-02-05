<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Entity\User;
use App\Security\TokenGenerator;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $faker;
    private const TOTAL_BLOG_POST = 100;
    private const USERS = [
        [
            'username'    => 'admin',
            'email'       => 'admin@blog.com',
            'name'        => 'Piotr Jura',
            'password'    => 'secret123',
            'roles'       => [User::ROLE_SUPERADMIN],
            'enabled'     => true
        ],
        [
            'username'    => 'jhon_doe',
            'email'       => 'jhon@blog.com',
            'name'        => 'John Doe',
            'password'    => 'secret123',
            'roles'       => [User::ROLE_ADMIN],
            'enabled'     => true
        ],
        [
            'username'    => 'rob_smith',
            'email'       => 'rob@blog.com',
            'name'        => 'Rob Smith',
            'password'    => 'secret123',
            'roles'       => [User::ROLE_WRITER],
            'enabled'     => true
        ],
        [
            'username'    => 'jenny_rowling',
            'email'       => 'jenny@blog.com',
            'name'        => 'Jenny',
            'password'    => 'secret123',
            'roles'       => [User::ROLE_WRITER],
            'enabled'     => true,
        ],
        [
            'username'    => 'han_solo',
            'email'       => 'han@blog.com',
            'name'        => 'Han Solo',
            'password'    => 'secret123',
            'roles'       => [User::ROLE_EDITOR],
            'enabled'     => false,
        ],
        [
            'username'    => 'jedi_knight',
            'email'       => 'jedi@blog.com',
            'name'        => 'Jedi Knight',
            'password'    => 'secret123',
            'roles'       => [User::ROLE_COMMENTATOR],
            'enabled'     => true,
        ]
    ];
    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;


    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param TokenGenerator $tokenGenerator
     */
    public function __construct(
        UserPasswordEncoderInterface $encoder,
        TokenGenerator $tokenGenerator

    )
    {
        $this->passwordEncoder = $encoder;
        $this->faker = \Faker\Factory::create('FR');
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadBlogPosts(ObjectManager $manager)
    {

        $total = self::TOTAL_BLOG_POST;

        for ($i=0; $i<$total; $i++)
        {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            if ($i > ($total/2))
            {
                $blogPost->setPublished($this->faker->dateTimeThisYear);
            }
            else
                {
                    $blogPost->setPublished($this->faker->dateTime);
                }

            $authorReference = $this->getRandomUserReference($blogPost);

            $blogPost->setAuthor($authorReference);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setSlug($this->faker->slug);

            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

        for ($i=0; $i<self::TOTAL_BLOG_POST; $i++)
        {
            for($j=0; $j < rand(1, 10); $j++)
            {
                $comment = new Comment();
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setContent($this->faker->realText());

                $authorReference = $this->getRandomUserReference($comment);

                $comment->setAuthor( $authorReference);
                $comment->setBlogPost($this->getReference("blog_post_$i"));
                $manager->persist($comment);

            }
        }
        $manager->flush();
    }

    public function loadUsers(ObjectManager $manager)
    {
        foreach(self::USERS as $userFixtures)
        {
            $user = new User();
            $user->setUsername($userFixtures['username']);
            $user->setEmail($userFixtures['email']);
            $user->setName($userFixtures['name']);

            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userFixtures['password']
            ));
            $user->setRoles($userFixtures['roles']);
            $user->setEnabled($userFixtures['enabled']);

            if (!$userFixtures['enabled']){
                $user->setConfirmationToken(
                    $this->tokenGenerator->getRandomSecureToken()
                );
            }

            $this->addReference('user_' . $userFixtures['username'], $user);

            $manager->persist($user);
        }
        $manager->flush();

    }

    /**
     * @return User
     */
    protected function getRandomUserReference($entity): User
    {
        $randomUser = self::USERS[rand(0, 5)];

        if ($entity instanceof BlogPost &&
            !count(array_intersect($randomUser['roles'],
                [User::ROLE_SUPERADMIN, User::ROLE_ADMIN, User::ROLE_WRITER]
            ))) {
            return $this->getRandomUserReference($entity);
        }
        if ($entity instanceof Comment &&
            !count(array_intersect($randomUser['roles'],
                [
                    User::ROLE_SUPERADMIN,
                    User::ROLE_ADMIN,
                    User::ROLE_WRITER,
                    User::ROLE_COMMENTATOR
                ]
            ))) {
            return $this->getRandomUserReference($entity);
        }

        return $this->getReference('user_' . $randomUser['username']);
    }
}