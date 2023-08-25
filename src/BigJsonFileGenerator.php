<?php

namespace Elminson\ReadBigJson;
use Iterator;

class BigJsonFileGenerator implements Iterator {
	public $fileHandle;
	private $currentPosition = 0;
	private $totalItems;

	public function __construct($fileName, $totalItems) {
		$this->fileHandle = fopen($fileName, 'w');
		$this->totalItems = $totalItems;
	}

	public function current(): mixed {
		$name = $this->generateRandomName();
		$data = [
			'id' => $this->currentPosition + 1,
			'name' => $name,
			'contact' => [
				'email' => 'danieljones@example.com',
				'phone' => $this->generateRandomPhoneNumber()
			],
			'age' => mt_rand(18, 60),
		];
		return json_encode($data);
	}

	public function key(): mixed {
		return $this->currentPosition;
	}

	public function next(): void {
		$this->currentPosition++;
	}

	public function rewind(): void {
		$this->currentPosition = 0;
	}

	public function valid(): bool {
		return $this->currentPosition < $this->totalItems;
	}

	public function __destruct() {
		fclose($this->fileHandle);
	}

	private function generateRandomName() {
		$names = ['Michael', 'John', 'Emily', 'Jessica', 'Christopher', 'Jennifer', 'Daniel', 'Sarah'];
		$lastNames = ['Smith', 'Johnson', 'Williams', 'Jones', 'Brown', 'Davis', 'Miller'];

		$randomName = $names[array_rand($names)] . ' ' . $lastNames[array_rand($lastNames)];
		return $randomName;
	}

	private function generateRandomEmail() {
		$domains = ['example.com', 'test.net', 'sample.org', 'domain.com'];
		$randomName = strtolower($this->generateRandomName());
		$randomDomain = $domains[array_rand($domains)];
		return $randomName . '@' . $randomDomain;
	}

	private function generateRandomPhoneNumber() {
		$digits = mt_rand(1000000000, 9999999999);
		return sprintf('%s-%s-%s', substr($digits, 0, 3), substr($digits, 3, 3), substr($digits, 6, 4));
	}

}
