<?php

namespace Client\Bundle\GameBundle\Controller;

use Client\Bundle\GameBundle\Entity\GameRepository;
use Client\Bundle\GameBundle\Entity\Point;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamsController extends Controller
{
    /**
     * @Route("/new/{gameId}")
     * @Template()
     */
    public function newAction(Request $request, $gameId)
    {
        $form = $this->createForm('client_bundle_gamebundle_team');
        $form->add('submit', 'submit');

        if ($request->isMethod('POST') && $form->submit($request)->isValid()) {
            /** @var GameRepository $gameRepository */
            $gameRepository = $this->getDoctrine()->getManager()->getRepository('ClientGameBundle:Game');
            $gameEntity = $gameRepository->find($gameId);

            $teamEntity = $form->getData();
            $teamEntity->addPlayer($this->getUser());

            $gameEntity->addTeam($teamEntity);
            $this->getDoctrine()->getManager()->persist($gameEntity);
            $this->getDoctrine()->getManager()->persist($teamEntity);
            $this->getDoctrine()->getManager()->flush();

            return new RedirectResponse($this->generateUrl('client_game_games_index'));
        }

        return array(
            'form'  => $form->createView(),
        );
    }

    /**
     * @Route("/Join/{id}")
     * @param Request $request
     */
    public function joinAction(Request $request, $id)
    {
        $teamRepository = $this->getDoctrine()->getManager()->getRepository('ClientGameBundle:Team');
        $teamEntity = $teamRepository->find($id);
        $teamEntity->addPlayer($this->getUser());
        $this->getDoctrine()->getManager()->persist($teamEntity);
        $this->getDoctrine()->getManager()->flush();
        return new RedirectResponse($this->generateUrl('client_game_games_index'));
    }

    /**
     * @Route("/point/{id}")
     * @param Request $request
     * @param $id
     */
    public function pointAction(Request $request, $id)
    {
        /** @var EntityRepository $teamRepository */
        $teamRepository = $this->getDoctrine()->getManager()->getRepository('ClientGameBundle:Team');
        $teamEntity = $teamRepository->find($id);
        $teamEntity->addPoint(new Point());
        $this->getDoctrine()->getManager()->persist($teamEntity);
        $this->getDoctrine()->getManager()->flush();

        if ($request->isXmlHttpRequest()) {

            $thunderClient = $this->get('client_bundle.gamebundle.thunder');

            $thunderClient->send_message_to_channel('teams', json_encode(array(
                'team' => $teamEntity->getId(),
                'points' => count($teamEntity->getPoints()),
            )));

            return new Response('ok');
        }

        return new RedirectResponse($this->generateUrl('client_game_games_view', array('id' => $teamEntity->getGame()->getId())));
    }
}
