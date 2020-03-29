<?php

namespace AppBundle\Entity;

use AppBundle\Validator\Constraints as AcmeAssert;

/**
 * Služi za filtriranje po prioritetu ili statusu
 */
class SearchParameters
{
	public $priority;

	public $status;
}