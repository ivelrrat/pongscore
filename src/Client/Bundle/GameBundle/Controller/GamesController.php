<?php

namespace Client\Bundle\GameBundle\Controller;

use Client\Bundle\GameBundle\Entity\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class GamesController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getManager()->getRepository('ClientGameBundle:Game');

        $games = $gameRepository->findAll();

        return array('games' => $games);
    }

    /**
     * @Route("/new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm('client_bundle_gamebundle_game');
        $form->add('submit', 'submit');

        if ($request->isMethod('POST') && $form->submit($request)->isValid()) {
            $gameEntity = $form->getData();
            $gameEntity->setOwner($this->getUser());
            $this->getDoctrine()->getManager()->persist($gameEntity);
            $this->getDoctrine()->getManager()->flush();

            return new RedirectResponse($this->generateUrl('client_game_games_index'));
        }

        return array(
            'form'  => $form->createView(),
        );
    }

    /**
     * @Route("/view/{id}")
     * @Template()
     * @param Request $request
     */
    public function viewAction(Request $request, $id)
    {
        /** @var GameRepository $gameRepository */
        $gameRepository = $this->getDoctrine()->getManager()->getRepository('ClientGameBundle:Game');

        $gameEntity = $gameRepository->find($id);

        return array(
            'game' => $gameEntity,
        );
    }
}
