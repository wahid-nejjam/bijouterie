<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommandeRepository;
use App\Service\panier\PanierService;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    //cette fonction affiche la page daccueil doù sa route configurée sur "/"
    //toute fonction possédant un return necessite une route en paramètre (gerer par annotation\route
    //ainsi qun name qui permettra dappeler cette fonction dans notre twig
    /**
     * @Route ("/", name="home")
     */
    public function home(ArticleRepository $articleRepository,CategorieRepository $categorieRepository,PanierService $panierService, PaginatorInterface $paginator, Request $request)//ici on injecte la dépendance de ArticleRepository afin de pouvoir utiliser les methodes de la class ArticleRepository
        //taper pubf pour avoir public function
    {

        $prenom = "wahid";
        $nom = "nejjam";
        $age = "18";
        $panier = $panierService->getFullPanier();
        $categories=$categorieRepository->findAll();

            $articles = $articleRepository->findAll();//on utilise la methode findAll de ArticleRepository afin de faire une requête de select * de nos articles que nous allons transmettre à notre vue twig

        $articles=$paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('front/home.html.twig',[
            'prenom' => $prenom,
            'nom' => $nom,
            'age' => $age,
            'articles' => $articles,
            'panier'=>$panier,
            'categories'=>$categories
        ]);
    }

    /**
     * @Route ("/homefilter", name="homefilter")
     */
    public function homeFilter(ArticleRepository $articleRepository, CategorieRepository $categorieRepository, PanierService $panierService, PaginatorInterface $paginator, Request $request)
    {
        $categories=$categorieRepository->findAll();
        $panier = $panierService->getFullPanier();

        $prixmax=$request->request->get('prixmax');
        $cat=$request->request->get('cat');

        if($cat=='all' && $prixmax==50):
        $articles=$articleRepository->findAll();
        elseif ($cat!=='all' && $prixmax==50):
            $articles=$articleRepository->findBy(['categorie'=>$cat],['prix'=>'ASC']);
        elseif ($cat=='all' && $prixmax!==50):
            $articles=$articleRepository->findByPrice($prixmax);
        elseif ($cat!=='all' && $prixmax!==50):
            $articles=$articleRepository->findByCategoryAndPrice($cat, $prixmax);
        endif;

        $articles=$paginator->paginate(
            $articles,
            $request->query->getInt('page', 1),
            6
        );


        return $this->render("front/home.html.twig",[

            'articles' => $articles,
            'panier'=>$panier,
            'categories'=>$categories

        ]);
    }

    /**
     * @Route ("commandes_user", name="commandes_user")
     */
    public function commandes_user(CommandeRepository $repository)
    {
        $commandes = $repository->findBy(['user' => $this->getUser()], ['id' => 'DESC']);
        return $this->render("front/commandes_user.html.twig", [
            'commandes' => $commandes

        ]);

    }

    /**
     * @Route ("mail_form", name="mail_form")
     */
    public function mail_form()
    {
        return $this->render('front/mail_form.html.twig');
    }


    /**
     * @Route ("mail_template", name="mail_template")
     */
    public function mail_template()
    {
        return $this->render('front/mail_template.html.twig');
    }

}


