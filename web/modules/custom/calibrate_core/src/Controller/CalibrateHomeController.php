<?php

namespace Drupal\calibrate_core\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides the rest output.
 */
class CalibrateHomeController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this form class.
    return new static(
    );
  }

  /**
   * Display empty page.
   */
  public function homepage() {
    return [];
  }

}
