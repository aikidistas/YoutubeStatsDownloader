<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\VersionedLike;
use App\Entity\Video;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    /**
     * @Route("/", name="dashboard")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        // +: list videos
        // +: add form to filter videos
        // TODO: add autocomplete tag in form
        // TODO: add video title

        $videos = array();

        $form = $this->createFormBuilder(array('tag'=>'top'))
            ->add('tag', TextType::class, array(
                'required' => false
            ))
//            ->add('video_performance', NumberType::class)
            ->add('submitFilter', SubmitType::class, array('label' => 'Filter videos'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $form->getData()['tag'] !== null) {
            $data = $form->getData();
            $videos = $this->getDoctrine()
                ->getRepository(Video::class)
                ->findByTag($data['tag']);
        } else {
            $videos = $this->getDoctrine()
                ->getRepository(Video::class)
                ->findAll();
        }

        return $this->render('dashboard.html.twig', array(
            'videos' => $videos,
            'form' => $form->createView(),
        ));
    }
}