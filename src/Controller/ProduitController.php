<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/produit')]
class ProduitController extends AbstractController
{
    #[Route('/', name: 'app_produit_index', methods: ['GET', 'POST'])]
    public function index(ProduitRepository $produitRepository,Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
       /* if (!$session->has('login') || $session->get('login') === 0) {
            return $this->redirectToRoute('login');
        }*/
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            ////////////////:photo
            $tempFilePath = $form['photo']->getData();
            $randomNumber = rand(10000, 99999); // Génère un nombre aléatoire entre 10000 et 99999
            $destinationPath = "produit_photos/" . $produit->getNom() . $randomNumber . ".png";
            
            $compressionQuality = 100;
    
            $this->compressImage($tempFilePath, $destinationPath, $compressionQuality);
    
            $produit->setPhoto($destinationPath);

            //////////////////
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {/*  if (!$session->has('login') || $session->get('login') === 0) {
        return $this->redirectToRoute('login');
    }  */
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            ////////////////:photo
            $tempFilePath = $form['photo']->getData();
            $randomNumber = rand(10000, 99999); // Génère un nombre aléatoire entre 10000 et 99999
            $destinationPath = "produit_photos/" . $produit->getNom() . $randomNumber . ".png";
            
            $compressionQuality = 100;
    
            $this->compressImage($tempFilePath, $destinationPath, $compressionQuality);
    
            $produit->setPhoto($destinationPath);

            //////////////////
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_show', methods: ['GET'])]
    public function show(Produit $produit, SessionInterface $session): Response
    {/*  if (!$session->has('login') || $session->get('login') === 0) {
        return $this->redirectToRoute('login');
    }  */
        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Produit $produit, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {
       /* if (!$session->has('login') || $session->get('login') === 0) {
            return $this->redirectToRoute('login');
        }*/
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_produit_delete', methods: ['POST'])]
    public function delete(Request $request, Produit $produit, EntityManagerInterface $entityManager, SessionInterface $session): Response
    {

        /*if (!$session->has('login') || $session->get('login') === 0) {
            return $this->redirectToRoute('login');
        }*/
            $entityManager->remove($produit);
            $entityManager->flush();
        

        return $this->redirectToRoute('app_produit_index', [], Response::HTTP_SEE_OTHER);
    }
    private  function compressImage($source, $destination, $quality) {
        $info = getimagesize($source);
        
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source);
        } else {
            return false;
        }    // Sauvegarder l'image compressée
           imagejpeg($image, $destination, $quality);
           
           // Libérer la mémoire
           imagedestroy($image);
           
           return true;
       }

    #[Route('/update-quantity', name: 'update_quantity', methods: ['POST'])]

    public function updateQuantity(Request $request, EntityManagerInterface $entityManager, SessionInterface $session)
    {
       /* if (!$session->has('login') || $session->get('login') === 0) {
            return $this->redirectToRoute('login');
        }*/
        $data = json_decode($request->getContent(), true);

       

        $produit = $entityManager->getRepository(Produit::class)->find($data['id']);

        if (!$produit) {
            return new JsonResponse(['error' => 'Produit non trouvé'], JsonResponse::HTTP_NOT_FOUND);
        }

        $produit->setQuantite($data['quantite']);
        $entityManager->persist($produit);
        $entityManager->flush();

        return new JsonResponse(['success' => 'Quantité mise à jour avec succès'], JsonResponse::HTTP_OK);
    }
}
