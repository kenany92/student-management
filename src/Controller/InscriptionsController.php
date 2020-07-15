<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Eleve;
use App\Repository\UserRepository;
use App\Repository\EleveRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EleveType;
use App\Form\SupprimerEleveType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class InscriptionsController extends AbstractController
{
    
    /**
     * @Route("/inscriptions", name="inscriptions")
     */
    public function index(Request $request, EleveRepository $eleveRepository, TranslatorInterface $translator)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();
        if($auth_checker && $user != "anon."){
            $eleve = new Eleve();
            $error="";
            $form = $this->createForm(EleveType::class, $eleve);
            $form->handleRequest($request);
            $formSupprimer = $this->createForm(SupprimerEleveType::class, $eleve);
            $formSupprimer->handleRequest($request);
    
            if($form->isSubmitted() && $form->isValid()){
                $eleve->setMatricule($this->random(10));
                $eleve->setStatut(true);
                $eleve->setDateInscription(new \DateTime('now'));
                $eleve->setDateDerniereModif(new \DateTime('now'));
    
                if($eleve->getMontant() < 5000){
                    $error= $translator->trans("Le montant de l'inscription ne doit pas être inférieur à 5000 !");
                }
                else{
                    if($eleve->getPhoto() == "" || $eleve->getPhoto() == null ){
                        $eleve->setPhoto("/uploads/eleve/photos/default_photo.jpg");
                    }
                    else{
                        $file = $form['photo']->getData();
                        $directory="../public/uploads/eleve/photos/";
                        $extension = $file->guessExtension();
                        if (!$extension) {
                           // extension cannot be guessed
                           $extension = 'bin';
                        }
                        $newFileName = $this->random(20).'.'.$extension;
                        $file->move($directory, $newFileName);
                        $eleve->setPhoto("/uploads/eleve/photos/".$newFileName);
                    }
                    $en = $this->getDoctrine()->getManager();
                    $en->persist($eleve);
                    $en->flush();
                }
            }
            if($error != ""){
                $this->addFlash('message', $error);
            }
            
            $listTitle = $translator->trans("Liste des élèves inscrits :");
            return $this->render('inscriptions/index.html.twig', [
                'controller_name' => 'InscriptionsController',
                'form' => $form->createView(),
                'formSupprimer' => $formSupprimer->createView(),
                'error' => $error,
                'eleves' => $eleveRepository->findByStatut(true),
                'list_title' => $listTitle
            ]);
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }

    /**
     * @Route("/inscriptions/supprimerEleve/{id}", name="supprimer")
     */

    public function supprimerEleve($id, EleveRepository $eleveRepository)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();
        if($auth_checker && $user != "anon."){
            $entityManager = $this->getDoctrine()->getManager();
        $eleve = $entityManager->getRepository(Eleve::class)->find($id);
    
        if (!$eleve) {
            throw $this->createNotFoundException(
                'No eleve found for id '.$id
            );
        }
    
        $eleve->setStatut(false);
        $entityManager->flush();
    
        $form = $this->createForm(EleveType::class, new Eleve);
    
        return $this->redirectToRoute("inscriptions");  
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }

    /**
     * @Route("/inscriptions/editerEleve/{id}", name="editer")
     */

    public function editerEleve(Request $request, EleveRepository $eleveRepository)
    {
        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();
        if($auth_checker && $user != "anon."){
            if ($request->getMethod() == 'POST') {
                  
                $post = $request->request;
                    $entityManager = $this->getDoctrine()->getManager();
                    $eleve = $entityManager->getRepository(Eleve::class)->find($post->get('id'));
    
        if (!$eleve) {
            throw $this->createNotFoundException(
                'No eleve found for id '.$id
            );
        }
        if($post->get('photo') != null){
            $file = $post->get('photo');
                        $directory="../public/uploads/eleve/photos/";
                        $extension = $file->guessExtension();
                        if (!$extension) {
                           // extension cannot be guessed
                           $extension = 'bin';
                        }
                        $newFileName = $this->random(20).'.'.$extension;
                        $file->move($directory, $newFileName);
                        $eleve->setPhoto("/uploads/eleve/photos/".$newFileName);
        }
    
        if($post->get('date_naissance') != null){
            
            $eleve->setDateNaissance(new \DateTime($post->get('date_naissance')));
        }
        if($post->get('nom') != null || $post->get('nom')!=""){
            $eleve->setNom($post->get('nom'));
        }
        if($post->get('prenom') != null || $post->get('prenom')!=""){
            $eleve->setPrenom($post->get('prenom'));
        }
        if($post->get('classe') != null || $post->get('classe')!=""){
            $eleve->setClasse($post->get('classe'));
        }
        if($post->get('numero_parent') != null || $post->get('numero_parent')!=""){
            $eleve->setNumeroParent($post->get('numero_parent'));
        }
        
        $eleve->setDateDerniereModif(new \DateTime('now'));
        $entityManager->flush();    
        }  
            
    
        return $this->redirectToRoute("inscriptions"); 
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }

    /**
     * @Route("/inscriptions/rechercherEleve", name="rechercher")
     */

    public function rechercherEleve(Request $request, EleveRepository $eleveRepository, TranslatorInterface $translator)
    {
        $eleve = new Eleve();
            $error="";
            $form = $this->createForm(EleveType::class, $eleve);
            $form->handleRequest($request);
            $formSupprimer = $this->createForm(SupprimerEleveType::class, $eleve);
            $formSupprimer->handleRequest($request);

        $auth_checker = $this->get('security.authorization_checker');
        $token = $this->get('security.token_storage')->getToken();
        $user = $token->getUser();
        if($auth_checker && $user != "anon."){
            if ($request->getMethod() == 'POST') {
                  
                $post = $request->request;
                    $entityManager = $this->getDoctrine()->getManager();
                    $eleves = $entityManager->getRepository(Eleve::class)->findByNom($post->get('nom'));
    
        if (!$eleves) {
            $eleves = $entityManager->getRepository(Eleve::class)->findByPrenom($post->get('nom'));
            if(!$eleves){
                $eleves = $entityManager->getRepository(Eleve::class)->findByMatricule($post->get('nom'));
                if(!$eleves){
                    $eleves = $entityManager->getRepository(Eleve::class)->findByClasse($post->get('nom'));
                    if(!$eleves){
                        $message = $translator->trans("Aucun élève trouvé au nom/prénom/matricule/classe de : ");
                        $error = $message.$post->get('nom');
                        }
                }
            }
        }
        if(!$eleves){
            $message = $translator->trans("Résultat de la recherche pour le nom/prénom/matricule/classe : ");
            $listTitle = $message.$post->get('nom');
            return $this->render('inscriptions/index.html.twig', [
                'controller_name' => 'InscriptionsController',
                'form' => $form->createView(),
                'formSupprimer' => $formSupprimer->createView(),
                'error' => $error,
                'eleves' => $eleves,
                'list_title' => $listTitle
            ]);  
        }
        else{
            $message = $translator->trans("Résultat de la recherche pour le nom/prénom/matricule/classe : ");
            $listTitle = $message.$post->get('nom');
            $entityManager->flush(); 
            return $this->render('inscriptions/index.html.twig', [
                'controller_name' => 'InscriptionsController',
                'form' => $form->createView(),
                'formSupprimer' => $formSupprimer->createView(),
                'error' => $error,
                'eleves' => $eleves,
                'list_title' => $listTitle
            ]);
        }   
        }  
        }
        else{
            return $this->redirectToRoute("app_login");
        }
    }

    public function random($car) {
        $string = "";
        $chaine = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
        $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
        }
}
