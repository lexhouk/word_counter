<?php

namespace Drupal\word_counter\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Word Counter settings for this site.
 */
class WordCounterSettingsForm extends ConfigFormBase {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->entityTypeManager = $container->get('entity_type.manager');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['word_counter.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'word_counter_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['status'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Status'),
      '#description' => $this->t('Enable the counting of words in the Body field in entities of the Article content type and see the count results on the pages of these nodes.'),
      '#default_value' => $this->config('word_counter.settings')->get('status'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('word_counter.settings');
    $status = $form_state->getValue('status');

    if ($config->get('status') != $status) {
      $config->set('status', $status)->save();

      $this->entityTypeManager->getViewBuilder('node')->resetCache();
    }

    parent::submitForm($form, $form_state);
  }

}
