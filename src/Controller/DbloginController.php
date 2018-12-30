<?php
namespace Drupal\dblogin\Controller;
use Drupal\Core\Controller\ControllerBase;

class DbloginController extends ControllerBase {

    public function reddb() {
        $config = \Drupal::config('dblogin.settings');
        global $databases, $base_url, $base_root;
        $creds = $databases['default']['default'];

        $options = array('query' =>
          array($config->get('dblogin.dblogin_username', 'pma_username') => $creds['username'],
          $config->get('dblogin.dblogin_password', 'pma_password') => $creds['password']
        ));
        if ($config->get('dblogin.dblogin_secure', ($base_root == 'https'))) {
          header('Location: '
            . Url($config->get('dblogin.dblogin_server', $base_url . '/phpmyadmin/'), $options)
            . '&' .$config->get('dblogin.dblogin_extra', 'server=1'));
          exit();
        }
        else {
          return t('Database login redirections have been disabled because your site is not using secure HTTPS connections. To access the database, enable HTTPS for this site, or visit the !admin_page to enable insecure database logins.', array('!admin_page' => l(t('admin page'), 'admin/config/system/dblogin')));
        }
      
    }
    /**
     * replacement process callbacks.
     */
}