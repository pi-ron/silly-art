<?php

namespace Terminus\Commands;

use Terminus\Utils;
use Terminus\Commands\TerminusCommand;
use Terminus\Exceptions\TerminusException;

/**
 * Print the Pantheon art
 *
 * @command sillyart
 */
class SillyArtCommand extends TerminusCommand {

  private $works = array('fright', 'chilling', 'fatpanda', 'happypanda', 'rude', 'zapme', 'gotmilk', 'rebelscum', );

  /**
   * View Silly ASCII artwork
   *
   * ## OPTIONS
   * <fright|chilling|fatpanda|happypanda|rude|zapme|gotmilk|rebelscum>
   */
  public function __invoke($args, $assoc_args) {
    $artwork = $this->works[array_rand($this->works)];
    if (count($args) > 0) {
      $artwork = array_shift($args);
    }

    try {
      $artwork_content = file_get_contents(__DIR__ . "/assets/" . "$artwork.txt");
      $this->output()->line(
        $this->colorize("%g" . base64_decode($artwork_content) . "%n")
      );
    } catch (TerminusException $e) {
      $this->failure(
        'There is no source for the requested "{artwork}" artwork.',
        compact('artwork')
      );
    }
  }

  /**
   * Returns a colorized string
   *
   * @param string $string Message to colorize for output
   * @return string
   */
  private function colorize($string) {
    $colorization_setting = $this->runner->getConfig('colorize');
    $colorize             = (
      (($colorization_setting == 'auto') && !\cli\Shell::isPiped())
      || (is_bool($colorization_setting) && $colorization_setting)
    );
    $colorized_string     = \cli\Colors::colorize($string, $colorize);
    return $colorized_string;
  }

}
