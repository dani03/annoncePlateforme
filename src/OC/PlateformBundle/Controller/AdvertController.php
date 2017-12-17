<?php
namespace OC\PlateformBundle\Controller;

use OC\PlateformBundle\Entity\Advert;
use OC\PlateformBundle\Entity\Image;
use OC\PlateformBundle\Entity\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdvertController extends Controller
{



  // on ajoute le Request ici pour reqcuper la requete depuis le controller
    public function viewAction($id)
    {


        // nous allons recuperer les entités via notre repository
        $repository = $this->getDoctrine()->getManager()->getRepository("OCPlateformBundle:Advert");
        //  ensuite on recupere l'entité corespondante au repository pour recuperer l'annonce
        $advert = $repository->find($id);


        if (null === $advert)
        {
          throw new NotFoundHttpException("l'annonce d'id ".$id." n'existe pas");
        }

        // on recupere la liste des candidatures
        $em = $this->getDoctrine()->getManager();
        $listApplications = $em->getRepository("OCPlateformBundle:Application")->findBy(array('advert'=> $advert));

        return $this->render(
          'OCPlateformBundle:Advert:view.html.twig',
          array(
            'advert' => $advert,
            'listsApplications' => $listApplications
          ));
    }

    public function viewSlugAction($slug, $year, $format)
    {


      return new Response("on pourrait afficher l'annonce au slug ".$slug." créée en ".$year." au format ".$format);
    }



     public function indexAction($page)
     {
       if($page < 1)
       {
         throw new NotFoundHttpException("page ".$page." inexistante");

       }
      //  Notre liste d'annonce en dur

       $listAdverts = array(

         array(

           'title'   => 'Recherche développpeur Symfony',

           'id'      => 1,

           'author'  => 'Mahana',

           'content' => 'Nous recherchons un développeur Symfony débutant sur Paris. Blabla…',

           'date'    => new \Datetime()),

         array(

           'title'   => 'Mission de webmaster',

           'id'      => 2,

           'author'  => 'Issam et Ahmadou',

           'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',

           'date'    => new \Datetime()),

         array(

           'title'   => 'Offre de stage webdesigner',

           'id'      => 3,

           'author'  => 'Louise',

           'content' => 'Nous proposons un poste pour webdesigner. Blabla…',

           'date'    => new \Datetime())

       );

       $mailer = $this->container->get('mailer');

    return $this->render('OCPlateformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts
      ));
     }

     public function addAction(Request $request)
     {

       $advert = new Advert();
       $advert->setTitle("recherche developpeur symfony 3");
       $advert->setAuthor("mahana Delacour");
       $advert->setContent("nous recherchons un developpeur sur paris a plein temps");
      //  nous nedefinnissons ni la date ni la publications car les attributs sont definis dans le contructeur

      // nous creer notre premiere candidature (application) pour un advert(annonce)
      $application1 = new Application();
      $application1->setAuthor("olivia");
      $application1->setContent("je postule je fais du drag and drop");

      // nous creer notre 2e candidature (application) pour un advert(annonce)
       $application2 = new Application();
       $application2->setAuthor("jeremy");
       $application2->setContent("je postule pour cette offre symfony");

       // nous creer notre 3e candidature (application) pour un advert(annonce)
        $application3 = new Application();
        $application3->setAuthor("ahmadou");
        $application3->setContent("je postule pour pour faire du front");

      // maintenant nous lions les candidature a l'annonce
      $application1->setAdvert($advert);
      $application2->setAdvert($advert);
      $application3->setAdvert($advert);

      // ensuite on recupere l'entityManager
      $em = $this->getDoctrine()->getManager();

      // ensuite on persite l'entité
      $em->persist($advert);

      $em->persist($application1);
      $em->persist($application2);
      $em->persist($application3);

      // ensuite un flush tout ce qui a ete persisté
      $em->flush();







      // ici nous creons l'image
       $image = new Image();
       $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
       $image->setAlt(' job de rêve');

      //  maintenant nous lions notre image recuperer a notre annonce
       $advert->setImage($image);
      // nous recuperons l'entité manager
       $em = $this->getDoctrine()->getManager();

      //  pour commencer on persite l'entité
      $em->persist($advert);

      // ensuite on flush tout ce qui a ete persisté au dessus
       $em->flush();


      //  nous recuperons le service ici pour les antispam
       $antispam = $this->container->get('oc_plateform.antispam');

       $text = 'azertyuiopsdfghjazertyuiazertyuazertyuazertyuazertyuzertyuizertyurtyuiortyuio';
       if ($antispam->isSpam($text))
       {
         throw new \Exception("votre message a été detecté comme spam !");

       }
       // Ici, on s'occupera de la création et de la gestion du formulaire



       if ($request->isMethod('POST'))
       {
        //  ici on va recuperer la creation et la gestion du formulaire
        $request->getSession()->getFlashBag()->add('notice', 'annonce bien ajoutée');

        // ensuite on redirige vers la page de visualisation de l'annonce
        return $this->redirectToRoute('oc_plateforme_view', array('id' => $advert->getId()));
       }
         // Si on n'est pas en POST, alors on affiche le formulaire
         return $this->render('OCPlateformBundle:Advert:add.html.twig');

     }

     public function editAction($id, Request $request)
     {
      //  on va tout ad'bord recuperer l'annonce correspondante a l'id ($id)


        // if ($request->isMethod('POST'))
        // {
        //   $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifiée.');
        //
        //   return $this->redirectToRoute('oc_plateforme_view', array('id' => $id));
        // }


        $advert = array(

      'title'   => 'Recherche développpeur Symfony',

      'id'      => $id,

      'author'  => 'Alexandre',

      'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',

      'date'    => new \Datetime()

    );
        return $this->render('OCPlateformBundle:Advert:edit.html.twig', array(
          'advert' => $advert
        ));
     }


     public function deleteAction($id)
     {
      //  ici on va recuperer l'id correspondant



      //  ici nous allons gerer la suppression de l'annonce dans le easy
      return $this->render('OCPlateformBundle:Advert:delete.html.twig');
     }


     public function menuAction($limit)
     {
       $listAdverts = array(
         array('id'=> 7, 'title' => 'recherche developpeur symfony'),
         array('id' => 8, 'title' => 'recherche de repetiteur'),
         array('id' => 9, 'title' => 'Offre de stage webdesigner'),
       );

       return $this->render('OCPlateformBundle:Advert:menu.html.twig', array(
         'listAdverts' => $listAdverts
       ));
     }
}
