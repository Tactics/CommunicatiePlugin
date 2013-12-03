<?php
/**
 * ttCommunicatieException is thrown when an error occurs in
 * the ttCommunicatie plugin
 *
 * @package    symfony
 * @subpackage exception
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @author     Sean Kerr <sean@code-box.org>
 * @version    SVN: $Id$
 */
class ttCommunicatieException extends sfException
{
  /**
   * Class constructor.
   *
   * @param string The error message
   * @param int    The error code
   */
  public function __construct($message = null, $code = 0)
  {
    $this->setName('ttCommunicatieException');
    parent::__construct($message, $code);
  }
}