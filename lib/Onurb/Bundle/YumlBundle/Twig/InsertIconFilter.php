<?php
// src/OC/PlatformBundle/Twig/InsertIcon.php

namespace Onurb\Bundle\YumlBundle\Twig;

use Symfony\Bundle\FrameworkBundle\Routing\Router;

class InsertIconFilter extends \Twig_Extension
{
  private $router;

  public function __construct(Router $router){
    $this->router = $router;
  }
  /**
   * Modifie le rendu pour insérer l'icone doctrine dans la toolbar de dev
   *
   * @param \Twig_Markup $text
   * @return \Twig_Markup
   */
  public function insertIcon($text)
  {
    $html = '
    <div class="sf-toolbar-info-piece">
        <a href="'. $this->router->generate('doctrine_yuml_mapping') .'" target="_blank" title="Doctrine Mapping">
          <b>Doctrine Mapping</b>
          <span>
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABQAAAAaCAYAAAC3g3x9AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA3XAAAN1wFCKJt4AAAAB3RJTUUH3AwOFjIRE2xHhwAAAvZJREFUSMedlF9oVnUYxz/P77zzzRmruYELGhh48Qor3026sOlFUFHrQmMuvXAQZrS9r+DawkVSeCPsItmibbB3zMKSFBGLJcIuhnazaM2tZjRRr15LcP3BomKdc35PFxs7O2fnde92Lp/z/D6/7/N9nt8jRD7NPF3l4w0BO0CnHJFj0jc5RpGfiQZ8vB6gASgHedZX+drNpJ9ZM1BhWySSEGTEzaafXxNQ0NGYvA2iMuy1pvesGugk3feAWzG5SUTOetn07tUp7P7xd2d2Swo4FQtVueBlal8vGggg58/71nF6VfkGxIsWAeS8bPpQ7NllTTm8PeX7tgfhhbj/ocPCgNM32VIQuFDKR8D64oZEVeCM0z/VvKxkP5M+AgwuhUl1CtPSg2nsgMS6WI2KHPBbaztDCt3M9p2CvbLgT2Dw258gTzw1ryU/gz35Grj/xUm1in2ppP/7EaMgYHNRGAB//UFI7cEuSJQUaK7p1aYmR9xM3YuCXo616JFKTPsQUlkduPbTGHagHdy5OEtfNYLuLej5/V+xJ/ajN8YDpVt3YFq6oSQZo1P2iJ+pm1a0Zn5sS5HnmpGKx+H+bJC4fgNS3wgmGFvNz2C734C5v5cif0ko+tjiBa+0Ibv2FjUwUp3C7OvEnn5/aXiTCTUjfFsRY2iXhRICeYVHAexwP/LzbSh9GP78LcgqLcM0tsO6YN71zgz2XFeUdy+hwjTKk/NPxUW//SqcUlaB0zYYhk2NYk+9A370mcs1g8qFgiVtrMIc/RQ2bQ5g45exQ0djYAD2S+N4MgzkY6d137tIeVUA++Eq9rPjYG1c+l3Hc84YyU24qLbGKizbGMBufocd7ADPLdAgOSa5iX8Wt42bre0SpTPkyOYapOFNmM1jv/gw/nUAiH6c6Js6GFpfCuJlaz8Q5a2V9mAElnNc57DkJtzYBetl0w2qclIgtQIqD3Qm+ic/f+DGBtDjGP9e3cugDQL1CpUISVHuqHAd5aKTfOiSdI/9Gz37P6crCTosFB7sAAAAAElFTkSuQmCC" alt="Doctrine Mapping">
          </span>
        </a>
    </div>';

    $pos = strrpos($text, '<div class="sf-toolbar-info-piece');
    $debut = substr($text, 0, $pos) ;
    $fin = substr($text, $pos);
    $pos2 = strpos($fin,'</div>') + 6;
    $string = $debut . substr($fin,0, $pos2) . $html . substr($fin, $pos2);
    return new \Twig_Markup($string, 'UTF-8') ;
  }

  public function getFilters()
  {
    return array(
       new \Twig_SimpleFilter('add_yuml_icon', array($this, 'insertIcon'))
    );
  }

  public function getName()
  {
    return 'InsertIcon';
  }
}