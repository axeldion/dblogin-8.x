<?php

namespace Drupal\dblogin\Form;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class dbloginForm extends ConfigFormBase {
   
    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'dblogin_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {

        $form = parent::buildForm($form, $form_state);

        $config = $this->config('dblogin.settings');

        global $base_url, $base_root;
  $form['basic'] =
    array(
      '#type' => 'fieldset',
      '#title' => t('Basic settings'),
      '#description' => t('The DB login module allows you to quickly access your database in a management tool, usually phpMyAdmin.'),
      '#collapsed' => FALSE,
    );
  $title = t('Enable redirection');
  if ($base_root != 'https') {
    $title = t('Enable INSECURE redirection');
  }
  $description = t('Redirection will be allowed only if this is checked. By default, this is unchecked for non-HTTPS connections, so redirection is not allowed for clear text connections. To override this, check the box to allow redirections even if the connection is insecure and you are really certain you do not care about your database being accessible to any trivial eavesdropper.');
  // mark insecure settings
  if ($config->get('dblogin_secure', ($base_root == 'https')) && ($base_root != 'https')) {
    $description .= '<br /><br />';
    $description .= t('WARNING: the current setting is INSECURE! Your database credentials will transmitted unencrypted. Uncheck this box or enable HTTPS on this site to use this module securely.');
  }
  $form['basic']['dblogin_secure'] =
    array(
     '#type' => 'checkbox',
     '#title' => $title,
     '#default_value' => $config->get('dblogin_secure', ($base_root == 'https')),
     '#description' => $description,
    );
  $form['basic']['dblogin_server'] =
    array(
     '#type' => 'textfield', 
     '#title' => t('Base URL'), 
     '#default_value' => $config->get('dblogin_server', $base_url . '/phpmyadmin/'), 
     '#description' => t('The base URL for the database administration site.'),
    );

  $form['advanced'] =
    array(
      '#type' => 'fieldset',
      '#title' => t('Advanced settings'),
      '#description' => t('You should not need to change those settings unless you are using an application other than phpMyAdmin or it is configured differently.'),
      '#collapsible' => TRUE, 
      '#collapsed' => TRUE,
    );
  $form['advanced']['dblogin_username'] =
    array(
     '#type' => 'textfield', 
     '#title' => t('Username field'), 
     '#default_value' => $config->get('dblogin_username', 'pma_username'), 
     '#description' => t('The HTTP query field name for the username field.'),
    );
  $form['advanced']['dblogin_password'] =
    array(
     '#type' => 'textfield', 
     '#title' => t('Password field'), 
     '#default_value' => $config->get('dblogin_password', 'pma_password'), 
     '#description' => t('The HTTP query field name for the username field.'),
    );
  $form['advanced']['dblogin_extra'] =
    array(
     '#type' => 'textfield', 
     '#title' => t('Extra parameters'), 
     '#default_value' => $config->get('dblogin_extra', 'server=1'), 
     '#description' => t('Extra query parameters to pass in the URL.'),
    );

        return parent::buildForm($form, $form_state);
    }

    public function submitForm(array &$form, FormStateInterface $form_state){
        $config = $this->config('dblogin.settings');
        $config->set('dblogin.secure', $form_state->getValue('dblogin_secure'));
        $config->set('dblogin.dblogin_server', $form_state->getValue('dblogin_server'));
        $config->set('dblogin.dblogin_username', $form_state->getValue('dblogin_username'));
        $config->set('dblogin.dblogin_password', $form_state->getValue('dblogin_password'));
        $config->set('dblogin.dblogin_extra', $form_state->getValue('dblogin_extra'));
        $config->save();
        return parent::submitForm($form, $form_state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return [
            'dblogin.settings',
        ];
    }

}
?>