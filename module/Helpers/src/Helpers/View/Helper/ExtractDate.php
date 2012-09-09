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
		// yyyy-mm-dd
		if (preg_match('/^([0-9]{4})\-([0-9]{2})\-([0-9]{2})$/', $date, $extract))
		{
			$output['year'] = $extract[1];
			$output['short_year'] = substr($extract[1], -2);
			$output['month'] = $extract[2];
			$output['day'] = $extract[3];
		}
		// yyyy-mm-dd hh:mm:ss
		else if (preg_match('/^([0-9]{4})\-([0-9]{2})\-([0-9]{2}) ([0-9]{2})\:([0-9]{2})\:([0-9]{2})$/', $date, $extract))
		{
			$output['year'] = $extract[1];
			$output['short_year'] = substr($extract[1], -2);
			$output['month'] = $extract[2];
			$output['day'] = $extract[3];
			$output['hour'] = $extract[4];
			$output['minute'] = $extract[5];
			$output['second'] = $extract[6];
		}
		// hh:mm:ss
		else if (preg_match('/^([0-9]{2})\:([0-9]{2})\:([0-9]{2})$/', $date, $extract))
		{
			$output['hour'] = $extract[1];
			$output['minute'] = $extract[2];
			$output['second'] = $extract[3];
		}
		// dd-mm-yyyy
		else if (preg_match('/^([0-9]{2})\-([0-9]{2})\-([0-9]{4})$/', $date, $extract))
		{
			$output['year'] = $extract[3];
			$output['short_year'] = substr($extract[3], -2);
			$output['month'] = $extract[2];
			$output['day'] = $extract[1];
		}
		return $output;
    }
}
