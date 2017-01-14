<?php

namespace Drupal\socializer\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class SocializerEvent
 */
class SocializerEvent extends Event {

  /**
   * @var array
   */
  protected $element;

  /**
   * Constructor
   */
  public function __construct($element) {
    $this->element = $element;
  }

  /**
   * Return the element
   * @return array()
   */
  public function getElement() {
    return $this->element;
  }

  /**
   */
  public function setElement($element) {
    $this->element = $element;
  }

}
