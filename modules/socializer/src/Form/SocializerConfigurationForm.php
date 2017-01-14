<?php

namespace Drupal\socializer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Form\FormStateInterface;


/**
 * Defines a form that configures forms module settings.
 */
class SocializerConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'socializer_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'socializer.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('socializer.settings');
    $networks = $this->getSocialNetworks();

    $form['display'] = array(
      '#type' => 'details',
      '#title' => t('Display Settings'),
      '#open' => True,
    );
    $form['display']['display_link_buttons_class'] = array(
      '#type' => 'textfield',
      '#title' => t('Link Buttons CSS Classes'),
      '#default_value' => $config->get('display_settings.link_buttons.class'),
      '#description' => t('Specify the default CSS class (or classes) for all link buttons. Separate multiple classes with a space. Can be overriden in block configuration. (Defaults: "soc-round" and "soc-square")'),
    );
    $form['display']['display_share_buttons_class'] = array(
      '#type' => 'textfield',
      '#title' => t('Share Buttons CSS Classes'),
      '#default_value' => $config->get('display_settings.share_buttons.class'),
      '#description' => t('Specify the default CSS class (or classes) for all share buttons. Separate multiple classes with a space. Can be overriden in block configuration. (Defaults: "soc-round" and "soc-square")'),
    );

    foreach ($networks as $key => $label) {
      $form[$key] = array(
        '#type' => 'details',
        '#title' => t('@network Settings', array('@network' => $label)),
        '#open' => FALSE,
      );
      $form[$key][$key . '_profile_enable'] = array(
        '#type' => 'checkbox',
        '#title' => t('Enable Profile Link'),
        '#default_value' => $config->get('social_networks.' . $key . '.profile_enable'),
        '#description' => t('Enable/disable this network&#8217s display in profile links blocks.'),
      );
      $form[$key][$key . '_share_enable'] = array(
        '#type' => 'checkbox',
        '#title' => t('Enable Share Link'),
        '#default_value' => $config->get('social_networks.' . $key . '.share_enable'),
        '#description' => t('Enable/disable this network&#8217s display in share links blocks.'),
      );
      $form[$key][$key . '_profile'] = array(
        '#type' => 'textfield',
        '#title' => t('Profile URL'),
        '#default_value' => $config->get('social_networks.' . $key . '.profile'),
        '#description' => t('The URL to your profile.'),
      );
      $form[$key][$key . '_api_url'] = array(
        '#type' => 'textfield',
        '#title' => t('Share API URL'),
        '#default_value' => $config->get('social_networks.' . $key . '.api_url'),
        '#description' => t('API URL for this social network. Uses Tokens (e.g. [current-page:url], [current-page:title]).'),
      );
      $form[$key][$key . '_profile_order'] = array(
        '#type' => 'number',
        '#title' => t('Profile Link Weight'),
        '#default_value' => $config->get('social_networks.' . $key . '.profile_order'),
        '#description' => t('The order you want this button in profile links blocks. (Example: 0 for first, 100 for always last)'),
      );
      $form[$key][$key . '_share_order'] = array(
        '#type' => 'number',
        '#title' => t('Network Share Weight'),
        '#default_value' => $config->get('social_networks.' . $key . '.share_order'),
        '#description' => t('The order you want this button in share links blocks. (Example: 0 for first, 100 for always last)'),
      );
      $form[$key][$key . '_icon_class'] = array(
        '#type' => 'textfield',
        '#title' => t('Font Awesome Icon Class'),
        '#default_value' => $config->get('social_networks.' . $key . '.icon_class'),
        '#description' => t('The Font Awesome icon to use for this button. (Example: fa-facebook)'),
      );
    }

    $form['email'] = array(
      '#type' => 'details',
      '#title' => t('E-Mail Settings'),
      '#open' => FALSE,
    );
    $form['email']['email_profile_enable'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable E-Mail Link'),
      '#default_value' => $config->get('social_networks.email.profile_enable'),
      '#description' => t('Enable/disable e-mail display in profile links blocks.'),
    );
    $form['email']['email_share_enable'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable Share Link'),
      '#default_value' => $config->get('social_networks.email.share_enable'),
      '#description' => t('Enable/disable e-mail display in share links blocks.'),
    );
    $form['email']['email_profile'] = array(
      '#type' => 'textfield',
      '#title' => t('E-mail URL'),
      '#default_value' => $config->get('social_networks.email.profile'),
      '#description' => t('The e-mail address or URL you want this button to link to in profile links blocks. Preface e-email address with "mailto:"'),
    );
    $form['email']['email_profile_order'] = array(
      '#type' => 'number',
      '#title' => t('E-Mail Link Weight'),
      '#default_value' => $config->get('social_networks.email.profile_order'),
      '#description' => t('The order you want this button in profile links blocks. (Example: 0 for first, 100 for always last)'),
    );
    $form['email']['email_share_order'] = array(
      '#type' => 'number',
      '#title' => t('E-Mail Share Weight'),
      '#default_value' => $config->get('social_networks.email.share_order'),
      '#description' => t('The order you want this button in share links blocks. (Example: 0 for first, 100 for always last)'),
    );
    $form[$key]['email_icon_class'] = array(
      '#type' => 'textfield',
      '#title' => t('Font Awesome Icon Class'),
      '#default_value' => $config->get('social_networks.email.icon_class'),
      '#description' => t('The Font Awesome icon to use for this button. (Example: fa-envelope-o)'),
    );
    $form[$key]['email_api_url'] = array(
      '#type' => 'textfield',
      '#title' => t('Share API URL'),
      '#default_value' => $config->get('social_networks.email.api_url'),
      '#description' => t('API URL for sharing via email. Uses Tokens (e.g. [current-page:url], [current-page:title]).'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('socializer.settings');
    $networks = $this->getSocialNetworks();

    $config->set('display_settings.link_buttons.class', $form_state->getValue('display_link_buttons_class'));
    $config->set('display_settings.share_buttons.class', $form_state->getValue('display_share_buttons_class'));


    foreach ($networks as $key => $label) {
      if ($form_state->hasValue($key . '_profile_enable')) {
        $config->set('social_networks.' . $key . '.profile_enable', $form_state->getValue($key . '_profile_enable'));
      }
      if ($form_state->hasValue($key . '_share_enable')) {
        $config->set('social_networks.' . $key . '.share_enable', $form_state->getValue($key . '_share_enable'));
      }
      if ($form_state->hasValue($key . '_profile')) {
        $config->set('social_networks.' . $key . '.profile', $form_state->getValue($key . '_profile'));
      }
      if ($form_state->hasValue($key . '_api_url')) {
        $config->set('social_networks.' . $key . '.api_url', $form_state->getValue($key . '_api_url'));
      }
      if ($form_state->hasValue($key . '_profile_order')) {
        $config->set('social_networks.' . $key . '.profile_order', $form_state->getValue($key . '_profile_order'));
      }
      if ($form_state->hasValue($key . '_share_order')) {
        $config->set('social_networks.' . $key . '.share_order', $form_state->getValue($key . '_share_order'));
      }
      if ($form_state->hasValue($key . '_icon_class')) {
        $config->set('social_networks.' . $key . '.icon_class', $form_state->getValue($key . '_icon_class'));
      }
    }
    if ($form_state->hasValue('email_profile_enable')) {
      $config->set('social_networks.email.profile_enable', $form_state->getValue('email_profile_enable'));
    }
    if ($form_state->hasValue('email_share_enable')) {
      $config->set('social_networks.email.share_enable', $form_state->getValue('email_share_enable'));
    }
    if ($form_state->hasValue('email_profile')) {
      $config->set('social_networks.email.profile', $form_state->getValue('email_profile'));
    }
    if ($form_state->hasValue('email_api_url')) {
      $config->set('social_networks.email.api_url', $form_state->getValue('email_api_url'));
    }
    if ($form_state->hasValue('email_profile_order')) {
      $config->set('social_networks.email.profile_order', $form_state->getValue('email_profile_order'));
    }
    if ($form_state->hasValue('email_share_order')) {
      $config->set('social_networks.email.share_order', $form_state->getValue('email_share_order'));
    }
    if ($form_state->hasValue('email_icon_class')) {
      $config->set('social_networks.email.icon_class', $form_state->getValue('email_icon_class'));
    }


    $config->save();
  }

  /**
   *
   * @return type
   */
   protected function getSocialNetworks() {
     $element = array(
       'facebook' => 'Facebook',
       'twitter' => 'Twitter',
       'linkedin' => 'LinkedIn',
       'googleplus' => 'Google Plus',
       'pinterest' => 'Pinterest',
       'behance' => 'Behance',
       'dribbble' => 'Dribbble',
       'youtube' => 'YouTube',
       'email' => 'email',
     );
     return $element;
   }
}

?>
