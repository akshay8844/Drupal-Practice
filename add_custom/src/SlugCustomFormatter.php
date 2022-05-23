<?php

namespace Drupal\add_custom;

use Cocur\Slugify\Slugify;

/**
 * Creates a class returning the slug with text and spearater provided 
 * with the help of cocour\slugify
 */
class SlugFormat {

  public function slugtext($str, $seperator) {
    $slugify = new Slugify();
    $formatted_slug = $slugify->slugify($str, $seperator);
    return $formatted_slug;
  }

}