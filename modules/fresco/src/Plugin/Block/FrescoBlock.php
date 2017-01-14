<?php

namespace Drupal\fresco\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Provides an image Block
 *
 * @Block(
 *   id = "fresco_block",
 *   admin_label = @Translation("Fresco Image Block"),
 * )
 */
class FrescoBlock extends BlockBase implements BlockPluginInterface {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $elements = array();
    $config = $this->getConfiguration();
    if (\Drupal::moduleHandler()->moduleExists('imce') && \Drupal\imce\Imce::access()) {
      $url = $config['img_ref'];
    } else {
      $file = File::load($config['img_ref'][0]);
      $url = file_create_url($file->getFileUri());
    }


    $build['fresco_block'] = array(
      '#theme' => 'fresco-block',
      '#image' => $url,
      '#attributes' => [
        'class' => $config['class'],
        'alt' => $config['alt'],
        'role' => $config['role'],
        'aria_label' => $config['aria_label']
        ],
    );
    return $build;
  }

  /**
  * {@inheritdoc}
  */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    if (\Drupal::moduleHandler()->moduleExists('imce') && \Drupal\imce\Imce::access()) {
      $form['image_upload'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Image'),
        '#description' => $this->t('Use IMCE to upload and/or link to internal image.'),
        '#attached' => array('library' => array('imce/drupal.imce.input')),
        '#attributes' => array('class' => array('imce-url-input')),
        '#default_value' => isset($config['img_ref']) ? $config['img_ref'] : '',
      );
    } else {
      $form['image_upload'] = array (
        '#type' => 'managed_file',
        '#title' => $this->t('Image'),
        '#default_value' => isset($config['img_ref']) ? $config['img_ref'] : '',
        '#upload_validators'  => array(
  		      'file_validate_extensions' => array('gif png jpg jpeg svg'),
  		      'file_validate_size' => array(25600000),
  	    ),
        '#upload_location' => 'public://media/images',
      );
    }

    $form['image_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Class'),
      '#description' => $this->t('Define a class for the image.'),
      '#default_value' => isset($config['class']) ? $config['class'] : '',
    );
    $form['image_alt'] = array(
      '#type' => 'textfield',
        '#size' => 80,
      '#title' => $this->t('Alt Text'),
      '#description' => $this->t('Set the alt text for the image.'),
      '#default_value' => isset($config['alt']) ? $config['alt'] : '',
    );
    $form['image_role'] = array(
      '#type' => 'textfield',
        '#size' => 80,
      '#title' => $this->t('Role'),
      '#description' => $this->t('Define a role for the image. (ARIA accessibility)'),
      '#default_value' => isset($config['role']) ? $config['role'] : '',
    );
    $form['image_aria_label'] = array (
      '#type' => 'textfield',
        '#size' => 80,
      '#title' => $this->t('ARIA Label'),
      '#description' => $this->t('Define a custom label for screen readers. (ARIA accessibility)'),
      '#default_value' => isset($config['aria_label']) ? $config['aria_label'] : '',
    );

    return $form;
  }

  /**
  * {@inheritdoc}
  */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->setConfigurationValue('class', $form_state->getValue('image_class'));
    $this->setConfigurationValue('alt', $form_state->getValue('image_alt'));
    $this->setConfigurationValue('role', $form_state->getValue('image_role'));
    $this->setConfigurationValue('aria_label', $form_state->getValue('image_aria_label'));

    if (\Drupal::moduleHandler()->moduleExists('imce') && \Drupal\imce\Imce::access()) {
      $this->setConfigurationValue('img_ref', $form_state->getValue('image_upload'));
    } else {
      $fid = $form_state->getValue('image_upload')[0];
      $this->saveImage($fid);

      $this->configuration['img_ref'] = [$fid];
    }
  }

  public function saveImage($fid) {
  $file = File::load( $fid );
  if ($file) {
    $file->setPermanent();
    $file->save();
  }
}

} // end class
