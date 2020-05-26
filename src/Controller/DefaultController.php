<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $fields = ['Мир', 'Спорт', 'Политика', 'Здоровье', 'Наука', 'Бизнес', 'Музыка'];
        $newsSlider = array();
        foreach ($fields as $field) :
            $newsSlider[] = $this->getDoctrine()
                ->getRepository(News::class)
                ->findByClassForSlider($field);
            endforeach;

        $newsMain= $this->getDoctrine()
            ->getRepository(News::class)
            ->findForMain();

        $newsAll = [];
        foreach ($fields as $field) :
            $newsAll[] = $this->getDoctrine()
                ->getRepository(News::class)
                ->findByClassForHome($field);
        endforeach;


        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'newsSlider' => $newsSlider,
            'newsAll' => $newsAll,
            'newsMain' => $newsMain,
        ]);
    }
}
