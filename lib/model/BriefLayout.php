<?php

/**
 * Subclass for representing a row from the 'brief_layout' table.
 *
 * 
 *
 * @package plugins.ttCommunicatiePlugin.lib.model
 */ 
class BriefLayout extends BaseBriefLayout
{
  /**
   * geef de string waarde van dit object
   *
   * @return string
   */
  public function __toString()
  {
    return $this->getNaam() ? $this->getNaam() : '';
  }
  
  
  /**
   * Include een bestand en geef het resultaat terug als string
   *
   * http://php.net/manual/en/function.include.php
   * 
   * @param string $filename
   * @return string
   */
  private function get_include_contents($filename)
  {
    if (is_file($filename))
    {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }

    return false;
  }
  
  /**
   * Geeft head en body terug
   * 
   * @param string $type : 'mail' of 'brief'
   * @param string $culture : 
   * 
   * @return string
   */
  public function getHeadAndBody($type, $culture, $html, $emailverzenden = false)
  {
    if ($type == 'mail')
    {
      $layout_bestand = $this->getMailBestand();
      $layout_stylesheets = $this->getMailStylesheets();
    }
    else
    {
      $layout_bestand = $this->getPrintBestand();
      $layout_stylesheets = $this->getPrintStylesheets();
    }
    
    // Lees alle stylesheets in
    $stylesheet_dir = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'stylesheets' . DIRECTORY_SEPARATOR;
    $css = '';
    foreach (explode(';', $layout_stylesheets) as $stylesheet)
    {
      $stylesheet_bestand = $stylesheet_dir . $stylesheet;
      @$stylesheet_css = $this->get_include_contents($stylesheet_bestand);
      if ($stylesheet_css)
      {
        $css .= $stylesheet_css;
      }
    }

    // Haal layout op en pas deze toe
    $layout_dir = sfConfig::get('sf_data_dir') . DIRECTORY_SEPARATOR . 'brieven' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR;
    
    // layout vertaald, haal files uit juiste culture subfolder
    if ($this->getVertaald())
    {
      $layout_dir .= $culture . DIRECTORY_SEPARATOR;
    }
    
    $html_layout = $this->get_include_contents($layout_dir . $layout_bestand);
    
    if ($html_layout)
    {
      Misc::use_helper('Url');      
      $html = strtr($html_layout, array(
        '%stylesheet%' => $css,
        '%body%' => $html,
        '%image_dir%' => $emailverzenden ? 'cid:' : url_for('ttCommunicatie/showImage') . '/image/'
      ));
    }

    // Knip het resulterende document op in stukken zodat we meerdere
    // brieven kunnen afdrukken zonder foute HTML te genereren (meerdere HEAD / BODY blokken)

    $startOpenBodyTag = stripos($html, '<body');
    $endOpenBodyTag = stripos($html, '>', $startOpenBodyTag);
    $endBodyTag = stripos($html, '</body>', $endOpenBodyTag);

    if (
      ($startOpenBodyTag === false)
      || ($endOpenBodyTag === false)
      || ($endBodyTag === false)
    )
    {
      throw new sfException('brief_layout "' . $layout_bestand . '" bevat geen geldige html body');
    }

    $bericht_body = substr($html, $endOpenBodyTag + 1, $endBodyTag - $endOpenBodyTag - 1);

    $startOpenHeadTag = stripos($html, '<head');
    $endOpenHeadTag = stripos($html, '>', $startOpenHeadTag);
    $endHeadTag = stripos($html, '</head>', $endOpenHeadTag);

    if (
      ($startOpenHeadTag === false)
      || ($endOpenHeadTag === false)
      || ($endHeadTag === false)
    )
    {
      throw new sfException('brief_layout "' . $layout_bestand . '" bevat geen geldige html head');
    }

    $bericht_head = substr($html, $endOpenHeadTag + 1, $endHeadTag - $endOpenHeadTag - 1);
    
    return array(
      'head' => $bericht_head,
      'body' => $bericht_body
    );

  }
}
