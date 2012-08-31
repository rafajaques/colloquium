<?php

/**
 * This class is an approach that I created to support the FormElement that
 * wasn't included in the first release of ZfcTwitterBootstrap
 */

namespace ZfcTwitterBootstrap\Form\View\Helper;

use Zend\Form\View\Helper\FormElement as MainFormElement;

use Zend\Form\ElementInterface;

class FormElement extends MainFormElement
{
	
	public function render(ElementInterface $element)
	{
		/* just a reminder. the types are:
			checkbox, color, date, datetime, datetime-local, email, file, hidden
			image, month, multi_checkbox, number, password, radio, range, reset
			search, select, submit, tel, text, textarea, time, url, week
		*/
		
		$labelHelper = $this->getLabelHelper();
        $escapeHelper = $this->getEscapeHtmlHelper();
        $elementHelper = $this->getElementHelper();
        $elementErrorHelper = $this->getElementErrorHelper();
        $descriptionHelper = $this->getDescriptionHelper();
        $groupWrapper = $groupWrapper ?: $this->groupWrapper;
        $controlWrapper = $controlWrapper ?: $this->controlWrapper;

        $id = $element->getAttribute('id') ?: $element->getAttribute('name');
        $html = "";

        $label = $element->getAttribute('label');
        if ($label) {
            $html .= $labelHelper->openTag(array(
                'for' => $id,
                'class' => 'control-label',
            ));
            // todo allow for not escaping the label
            $html .= $escapeHelper($label);
            $html .= $labelHelper->closeTag();
        }
        $html .= sprintf($controlWrapper,
            $id,
            $elementHelper->render($element),
            $descriptionHelper->render($element),
            $elementErrorHelper->render($element)
        );


        $addtClass = ($element->getMessages()) ? ' error' : '';
		
		echo $element->getLabel();die;
		
		$output .= parent::render($element);
		
		return $output;
	}
	
	/**
     * Get Label Helper
     *
     * @return Zend\Form\View\Helper\FormLabel
     */
    public function getLabelHelper()
    {
        if (!$this->labelHelper) {
            $this->setLabelHelper($this->view->plugin('formlabel'));
        }
        return $this->labelHelper;
    }
	
}