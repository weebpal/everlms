<?php

namespace Drupal\lms_base\Commands;

use Drupal\taxonomy\Entity\Term;
use Drush\Commands\DrushCommands;
use Drupal\commerce_product\Entity\Product;

/**
 * Drush command file.
 */
class LmsBaseCommands extends DrushCommands {

	/**
	 * A custom Drush command to move quiz value to quizzes value.
	 *
	 * @command lms_base:move_quiz_to_quizzes
	 *
	 *
	 * @aliases lb_move_quiz_to_quizzes
	 */
	public function moveQuizToQuizzesDrush() {
		$this->output()->writeln('Starting...');
		$product_ids = \Drupal::entityQuery('commerce_product')->accessCheck(FALSE)->execute();
		if ($product_ids) {
			$total = count($product_ids);
			$execute = 1;
			foreach ($product_ids as $product_id) {
				$product = Product::load($product_id);
				if ($product && $product->hasField('field_quiz') && !$product->get('field_quiz')->isEmpty() && $product->hasField('field_quizzes') && $product->get('field_quizzes')->isEmpty()) {
					$quiz = $product->get('field_quiz')->getValue();
					$product->set('field_quizzes', 	$quiz);
					$product->save();
					$this->output()->writeln(str_replace(['@execute', '@total'], [$execute, $total], 'Inprogress @execute/@total'));
					$execute++;
				}
			}
		}
		$this->output()->writeln('Finished');
	}

	/**
	 * A custom Drush command to set threshold for course.
	 *
	 * @command lms_base:set_threshold
	 *
	 * @param $threshold value, default 50
	 * @aliases lb_set_threshold
	 */
	public function setThresholdDrush($threshold = 50) {
		$this->output()->writeln('Starting...');
		$product_ids = \Drupal::entityQuery('commerce_product')->accessCheck(FALSE)->execute();
		if ($product_ids) {
			$total = count($product_ids);
			$execute = 1;
			foreach ($product_ids as $product_id) {
				$product = Product::load($product_id);
				if ($product && $product->hasField('field_threshold') && $product->get('field_threshold')->isEmpty()) {
					$product->set('field_threshold', 	$threshold);
					$product->save();
					$this->output()->writeln(str_replace(['@execute', '@total'], [$execute, $total], 'Inprogress @execute/@total'));
					$execute++;
				}
			}
		}
		$this->output()->writeln('Finished');
	}

	/**
	 * A custom Drush command to set key for quiz result.
	 *
	 * @command lms_base:set_key_quiz_result
	 *
	 * @aliases lb_set_key_quiz_result
	 */
	public function setKeyForQuizResultDrush() {
		$this->output()->writeln('Starting...');
		$terms = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['vid' => 'quiz_results']);
		/** @var Term $term */
		foreach ($terms as $term) {
			if ($term->hasField('field_key')) {
				$term_name = $term->getName();
				$term->set('field_key', strtolower($term_name));
				$term->save();
			}
		}
		$this->output()->writeln('Finished');
	}
}
