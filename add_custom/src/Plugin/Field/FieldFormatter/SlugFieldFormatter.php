<?php

namespace Drupal\add_custom\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Plugin implementation of the 'slug_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "slug_formatter",
 *   label = @Translation("Slug Formatter"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary"
 *   },
 *   quickedit = {
 *     "editor" = "form"
 *   }
 * )
 */
class SlugFieldFormatter extends FormatterBase {

      /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'slug_custom' => '-',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['slug_custom'] = [
      '#title' => t('Slug Format'),
      '#type' => 'textfield',
      '#field_suffix' => t('Slug Separator'),
      '#default_value' => $this->getSetting('slug_custom'),
      '#description' => t('Add the separator for the slug.'),
      '#min' => 1,
      '#required' => TRUE,
    ];
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = t('Slug Custom: @slug_custom added as slug separator', ['@slug_custom' => $this->getSetting('slug_custom')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    // $render_as_summary = function (&$element) {
    //   // Make sure any default #pre_render callbacks are set on the element,
    //   // because text_pre_render_summary() must run last.
    //   $element += \Drupal::service('element_info')->getInfo($element['#type']);
    //   // Add the #pre_render callback that renders the text into a summary.
    //   $element['#pre_render'][] = [TextTrimmedFormatter::class, 'preRenderSummary'];
    //   // Pass on the trim length to the #pre_render callback via a property.
    //   $element['#text_summary_trim_length'] = $this->getSetting('trim_length');
    // };

    // The ProcessedText element already handles cache context & tag bubbling.
    // @see \Drupal\filter\Element\ProcessedText::preRenderText()
    foreach ($items as $delta => $item) {

      $slug_text = \Drupal::service('add_custom.slug_custom_slugify')->slugtext($item->value,$this->getSetting('slug_custom') );


      $elements[$delta] = [
        '#type' => 'processed_text',
        '#text' => $slug_text,
        '#format' => $item->format,
        '#langcode' => $item->getLangcode(),
      ];

      if ($this->getPluginId() == 'text_summary_or_trimmed' && !empty($item->summary)) {
        $elements[$delta]['#text'] = $item->summary;
      }
      else {
        $elements[$delta]['#text'] = $item->value;
        $render_as_summary($elements[$delta]);
      }
    }

    return $elements;
  }
}