<?php

namespace Helpers\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ExtractDate extends AbstractHelper
{
    /**
     * __invoke
     *
     * @access public
     * @return String
     */
    public function __invoke($date)
    {
		$output = false;
		if (preg_match('/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})/', $date, $extract)) {
			$output['year'] = $extract[1];
			$output['short_year'] = substr($extract[1], -2);
			$output['month'] = $extract[2];
			$output['day'] = $extract[3];
		} else if (preg_match('/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/', $date, $extract)) {
			$output['year'] = $extract[3];
			$output['short_year'] = substr($extract[3], -2);
			$output['month'] = $extract[2];
			$output['day'] = $extract[1];
		}
		return $output;
    }
}
