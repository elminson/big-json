<?php

namespace Elminson\ReadBigJson;

use FilterIterator;
use Iterator;

class LazyFilterIterator extends FilterIterator {
	private $condition;

	public function __construct(Iterator $iterator, $condition) {
		parent::__construct($iterator);
		$this->condition = $condition;
	}

	public function accept(): bool {
		$item = $this->getInnerIterator()->current();
		return call_user_func($this->condition, $item);
	}
}