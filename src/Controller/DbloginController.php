<?php
namespace Drupal\dblogin\Controller;
use Drupal\Core\Controller\ControllerBase;

class DbloginController extends ControllerBase {

    public function reddb() {
          $build = [
            '#markup' => $this->t('DB Login'),
          ];
          return $build;
    }
    /**
     * replacement process callbacks.
     */
}