<?php
namespace OC\PlateformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlateformBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{
  // dans l'argument de la methode load $manager est l'entityManager

  public function load(ObjectManager $manager)
  {
    // listes des noms des categorie à ajouter
    $names = array(
      'Développement Web',
      'Développement mobile',
      'graphisme',
      'Intégration',
      'Réseau'
    );

    foreach ($names as $name) {
       $category = new category();
       $category->setName($name);

      //  on persiste notre categorie c'est a dire qu'on enregistre dans la base de donnée
       $manager->persist($category);

       $manager->flush();
    }
  }
}
