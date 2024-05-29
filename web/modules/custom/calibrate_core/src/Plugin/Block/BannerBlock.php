<?php

namespace Drupal\calibrate_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'BannerBlock' block.
 *
 * @Block(
 *  id = "banner_block",
 *  admin_label = @Translation("Banner block"),
 * )
 */
class BannerBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  private EntityTypeManager $entityTypeManager;

  /**
   * {@inheritDoc}
   */
  public function __construct(
    array $configuration,
          $plugin_id,
          $plugin_definition,
    EntityTypeManager $entityTypeManager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): RestOutputBlock|static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * Retrieves the entity type manager.
   *
   * @return \Drupal\Core\Entity\EntityTypeManager
   *   The entity type manager.
   */
  protected function entityTypeManager(): EntityTypeManager {
    return $this->entityTypeManager;
  }

  /**
   * {@inheritDoc}
   */
  public function blockForm($form, FormStateInterface $form_state): array {
    $config = $this->configuration;
    $form = parent::blockForm($form, $form_state);

    // Add image upload field with media library support.
    $form['banner_image'] = [
      '#type' => 'media_library',
      '#allowed_bundles' => ['image'],
      '#title' => $this->t('Image'),
      '#upload_location' => 'public://calibrate_core/images/',
      '#upload_validators' => [
        'file_validate_extensions' => ['png gif jpg jpeg'],
      ],
      '#default_value' => $this->configuration['banner_image'] ?? '',
      '#description' => $this->t('Upload an image using the media library.'),
    ];

    $form['banner_text'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Banner text'),
      '#default_value' => $config['banner_text'] ?? '',
    ];

    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    parent::blockSubmit($form, $form_state);

    $values = $form_state->getValues();

    $this->configuration['banner_image'] = $values['banner_image'];
    $this->configuration['banner_text'] = $values['banner_text'];
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Exception
   */
  public function build(): array {
    $mediaStorage = $this->entityTypeManager()->getStorage('media');

    /** @var \Drupal\media\Entity\Media $media */
    $media = $mediaStorage->load($this->configuration['banner_image']);

    return [
      '#theme' => 'banner_block',
      '#image' => [
        'url' => $media->field_media_image->entity->createFileUrl(FALSE),
        'alt' => $media->field_media_image->alt,
      ],
      '#text' => $this->configuration['banner_text'],
    ];
  }

}
