<?php

namespace Drupal\socializer\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Utility\Token;
use Drupal\Core\Template\Attribute;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;



/**
 * Provides social media profile links
 *
 * @Block(
 *   id = "socializer_links_block",
 *   admin_label = @Translation("Socializer Profile Links"),
 * )
 */
 class SocializerLinksBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The configFactory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The token service.
   *
   * @var \Drupal\Core\Utility\Token
   */
  protected $token;

  /**
   * Constructor
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ConfigFactoryInterface $config_factory, Token $token) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->configFactory = $config_factory;
    $this->token = $token;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration, $plugin_id, $plugin_definition, $container->get('config.factory'), $container->get('token'), $container->get('event_dispatcher')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $social_networks = $this->configFactory->get('socializer.settings')->get('social_networks');
    $networks = array();
    $config = $this->getConfiguration();
    $button_class = $config['class'];

    $social_networks = $this->sortNetworks($social_networks);

    foreach ($social_networks as $key => $label) {
      if ($label['profile_enable'] == 1 && !empty($label['profile'])) {
        $networks[$key]['text'] = $label['text'];
        $networks[$key]['profile'] = $label['profile'];
        $networks[$key]['icon_class'] = $label['icon_class'];
        $networks[$key]['name'] = $label['text'];
        $networks[$key]['name'] = strtolower($networks[$key]['name']);

      }
    }

    $build['socializer_links_block'] = array(
      '#theme' => 'social_network_links',
      '#elements' => $networks,
      '#buttons' => $button_class,
      '#attached' => array(
        'library' =>  array(
          'socializer/global-css',
        ),
      ),
    );

    return $build;

  }


  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['links_block_class'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('Custom Class'),
      '#description' => $this->t('Custom class override for block instance. Defaults: soc-round, soc-square'),
      '#default_value' => isset($config['class']) ? $config['class'] : '',
    );

    return $form;
  }

    /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('class', $form_state->getValue('links_block_class'));
  }


    /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    $default_config = \Drupal::config('socializer.settings');
    return array(
      'class' => $default_config->get('display_settings.link_buttons.class')
    );
  }

  protected function sortNetworks($element) {
    $weight = array();
    foreach ($element as $key => $weight_setting) {
      $weight[$key] = $weight_setting['profile_order'];
    }
    array_multisort($weight, SORT_ASC, $element);
    return $element;
  }




}

?>
