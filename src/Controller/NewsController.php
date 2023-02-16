<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private NewsRepository $newsRepository;
    private PaginatorInterface $paginator;
    public function __construct(
        NewsRepository $newsRepository,
        PaginatorInterface $paginator,
        EntityManagerInterface $entityManager
    )
    {
        $this->newsRepository = $newsRepository;
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/news", name="news_index")
     */
    public function index(Request $request): Response
    {

        $news = $this->newsRepository->findAll();

        $news = $this->paginator->paginate(
            $news,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('news/index.html.twig', [
            'news' => $news,
        ]);
    }

    /**
     * @Route("/news/{id}", name="delete_news", methods={"DELETE"})
     */
    public function delete(Request $request, News $news): Response
    {
        if ($this->isCsrfTokenValid('delete' . $news->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($news);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('article_index');
    }
}
