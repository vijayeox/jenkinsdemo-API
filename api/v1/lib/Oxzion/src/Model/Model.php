<?php

namespace Oxzion\Model;

abstract class Model{
	abstract public function exchangeArray($data);

	abstract public function toArray();
}