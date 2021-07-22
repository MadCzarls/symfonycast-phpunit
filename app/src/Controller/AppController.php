<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Enclosure;
use App\Factory\DinosaurFactory;
use App\Service\EnclosureBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use function mb_strtolower;
use function sprintf;

class AppController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager, EnclosureBuilder $enclosureBuilder): Response
    {
        $enclosures = $entityManager->getRepository(Enclosure::class)->findAll();

        return $this->render('test/index.html.twig', ['enclosures' => $enclosures]);
    }

    #[Route('/grow-dinosaur', name: 'grow_dinosaur', methods: ['POST'])]
    public function growDinosaur(
        Request $request,
        EntityManagerInterface $entityManager,
        DinosaurFactory $dinosaurFactory
    ): Response {
        $enclosure = $entityManager->getRepository(Enclosure::class)
            ->find($request->request->get('enclosure'));

        $specification = $request->request->get('specification');

        $dinosaur = $dinosaurFactory->growFromSpecification($specification);
        $dinosaur->setEnclosure($enclosure);
        $enclosure->addDinosaur($dinosaur);

        $entityManager->flush();
        $this->addFlash('success', sprintf(
            'Grew a %s in enclosure #%d',
            mb_strtolower($specification),
            $enclosure->getId()
        ));

        return $this->redirectToRoute('home');
    }
}
