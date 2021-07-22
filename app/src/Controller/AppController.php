<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Enclosure;
use App\Service\EnclosureBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager, EnclosureBuilder $enclosureBuilder): Response
    {
//        $enclosureBuilder->buildEnclosure();
        $enclosures = $entityManager->getRepository(Enclosure::class)->findAll();

        return $this->render('test/index.html.twig', ['enclosures' => $enclosures]);
    }
}
