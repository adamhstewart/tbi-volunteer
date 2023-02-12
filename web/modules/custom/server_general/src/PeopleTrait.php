<?php

namespace Drupal\server_general;

use Drupal\node\NodeInterface;
use Drupal\Core\Url;
use Drupal\taxonomy\TermInterface;

/**
 * Trait PeopleTrait.
 *
 * Helper method for building a person.
 */
trait PeopleTrait {

  /**
   * Get a person.
   *
   * @param Drupal\Core\Entity\NodeInterface $entity
   *   The entity to render.
   *
   * @return array
   *   Render array.
   */
  public function buildPerson(NodeInterface $entity) {
    return [
      '#theme' => 'server_theme_person',
      '#title' => $entity->label(),
    ];
  }

  /**
   * Build a list of people out of a field.
   *
   * @param \Drupal\Core\Entity\NodeInterface $entity
   *   The referencing entity.
   * @param string $field_name
   *   The field name. Defaults to `field_members`.
   *
   * @return array
   *   Render array.
   */
  public function buildPeople(NodeInterface $entity, string $field_name = 'field_members'): array {
    if (empty($entity->{$field_name}) || $entity->{$field_name}->isEmpty()) {
      return [];
    }

    $items = [];
    /** @var \Drupal\node\NodeInterface $node */
    foreach ($this->getReferencedEntitiesFromField($entity, $field_name) as $node) {
      $items[] = $this->buildPerson($node);
    }

    $title = $entity->{$field_name}->getFieldDefinition()->getLabel();

    return [
      '#theme' => 'server_theme_people',
      '#title' => $title,
      '#items' => $items,
    ];
  }

    /**
   * Get an officer.
   *
   * @param \Drupal\taxonomy\TermInterface $term
   *   The term to render.
  *
   * @return array
   *   Render array.
   */
  public function buildOfficer(TermInterface $term) {
    return [
      '#theme' => 'server_theme_person',
      '#title' => $term->label(),
      // As the style guide is using this with mocked terms (i.e. terms which
      // are not saved), we fallback to a link to the homepage.
      '#url' => !$term->isNew() ? $term->toUrl() : Url::fromRoute('<front>'),
    ];
  }

}
