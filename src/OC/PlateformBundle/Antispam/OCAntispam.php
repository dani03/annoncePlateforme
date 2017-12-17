<?php
namespace OC\PlateformBundle\Antispam;

class OCAntispam
{
  private $mailer;
  private $locale;
  private $minLength;

  public function __construct(\Swift_Mailer $mailer, $locale, $minLength)
  {
    $this->mailer = $mailer;
    $this->locale = $locale;
    $this->minLength = (int) $minLength;
  }
  /**
     * Vérifie si le texte est un spam ou pas
     *
     * @param string $text
     * @return bool
     */

    // on considere que si une annonce a moins de 50 catracteres est un Spam
    public function isSpam($text)
    {
        return strlen($text) < $this->minLength;

    }

}
