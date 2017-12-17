<?php
namespace OC\PlateformBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



class ByeByeController extends Controller
{
  public function ByeAction()
  {
    $resultat = $this
    ->get('templating')
    ->render('OCPlateformBundle:ByeBye:goodbye.html.twig');
    return new response($resultat);
  }
}
