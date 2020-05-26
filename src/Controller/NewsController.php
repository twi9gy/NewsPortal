<?php

namespace App\Controller;

use App\Entity\News;
use App\Form\CommentType;
use App\Entity\Comment;
use App\Form\NewsType;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/news")
 */
class NewsController extends AbstractController
{
    /**
     * @Route("/{class}", name="news_index", methods={"GET"})
     */
    public function index($class, NewsRepository $newsRepository): Response
    {
        switch ($class):
            case 'world':
                $field = 'Мир';
                break;
            case 'policy':
                $field = 'Политика';
                break;
            case 'sport':
                $field = 'Спорт';
                break;
            case 'health':
                $field = 'Здоровье';
                break;
            case 'science':
                $field = 'Наука';
                break;
            case 'business':
                $field = 'Бизнес';
                break;
            case 'music':
                $field = 'Музыка';
                break;
        endswitch;

        $news = $newsRepository->findByClass($field);
        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/show/{id}", name="news_show", methods={"GET"})
     */
    public function show(News $news, Request $request): Response
    {
        if ($this->getUser() != null) {
            if ($this->getUser()->getUsername() != $news->getAuthor()) {
                $news->plusCountViews();
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($news);
                $entityManager->flush();
            }
        }

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment,[
            'action' => $this->generateUrl('comment_new')
        ]);
        $form->handleRequest($request);


        $comments = $this->getDoctrine()
            ->getRepository(Comment::class)
            ->findByIdNews($news);

        return $this->render('news/show.html.twig', [
            'form' => $form->createView(),
            'news' => $news,
            'comments' => $comments,
        ]);
    }

    /**
     * @Route("/new/create", name="news_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if ($this->getUser() == null) {
            return $this->redirectToRoute('home');
        }

        $news = new News();
        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsFile = $form->get('src_img')->getData();

            if ($newsFile) {
                $newsFilename = md5(uniqid()) . '.' . $newsFile->guessExtension();
                $newsFile->move(
                    $this->getParameter('uploads_directory'),
                    $newsFilename
                );

                $news->setSrcImg($newsFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $news->setAuthor($this->getUser()->getUsername());
            $entityManager->persist($news);
            $entityManager->flush();
            return $this->redirectToRoute('news_show', ['id' => $news->getId()]);
        }

        return $this->render('news/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{id}", name="news_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, News $news): Response
    {
        if ($this->getUser() == null || $this->getUser()->getUsername() != $news->getAuthor()) {
            return $this->redirectToRoute('home');
        }

        $form = $this->createForm(NewsType::class, $news);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newsFile = $form->get('src_img')->getData();

            if ($newsFile) {
                $newsFilename = md5(uniqid()) . '.' . $newsFile->guessExtension();
                $newsFile->move(
                    $this->getParameter('uploads_directory'),
                    $newsFilename
                );
                $news->setSrcImg($newsFilename);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($news);
            $entityManager->flush();
            return $this->redirectToRoute('news_show', ['id' => $news->getId()]);
        }

        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="news_delete", methods={"DELETE"})
     */
    public function delete(Request $request, News $news): Response
    {
        if ($this->getUser() == null || $this->getUser()->getUsername() != $news->getAuthor()) {
            return $this->redirectToRoute('home');
        }

        $field = $news->getClass();

        if ($this->isCsrfTokenValid('delete'.$news->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($news);
            $entityManager->flush();
        }

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findByClass($field);

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);

    }

    /**
     * @Route("/private_office/", name="news_privateOffice", methods={"GET"})
     */
    public function index_world(NewsRepository $newsRepository): Response
    {
        $user = $this->getUser();
        $news = $newsRepository->findByClass(['author' => $user->getUsername()]);
        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }

}
