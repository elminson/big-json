<?php

namespace Elminson\ReadBigJson;

use IteratorAggregate;
use Traversable;

class BigJsonFileReader implements IteratorAggregate
{

	private $fileName;

	public function __construct($fileName)
	{

		$this->fileName = $fileName;
	}

	public function getIterator()
	: Traversable
	{

		return $this->read();
	}

	private function read()
	: \Generator
	{

		$fileHandle = fopen($this->fileName, 'r');
		while (!feof($fileHandle)) {
			$line = fgets($fileHandle);
			if ($line !== false) {
				yield json_decode($line, true);
			}
		}
		fclose($fileHandle);
	}

	public function where($field, $operator, $value)
	: LazyFilterIterator {

		return new LazyFilterIterator($this->read(), function ($item) use ($field, $operator, $value)
		{

			return $this->checkNestedField($item, $field, $operator, $value);
		});
	}

	private function checkNestedField($item, $field, $operator, $value)
	{

		$fieldParts = explode('.', $field);
		foreach ($fieldParts as $part) {
			if (isset($item[$part])) {
				$item = $item[$part];
			} else {
				return false;
			}
		}

		switch ($operator) {
			case '=':
				return $item === $value;
			case '>':
				return $item > $value;
			case '<':
				return $item < $value;
			// Add more operators as needed
		}

		return false;
	}

	public function first()
	{

		foreach ($this->read() as $item) {
			return $item;
		}

		return null;
	}
}
