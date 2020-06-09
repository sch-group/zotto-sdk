<?php


namespace SchGroup\Zotto\Helpers;


use DOMXPath;
use DOMDocument;
use SchGroup\Zotto\Components\PaymentForm;

class FormParser
{
    /**
     * @param string $html
     * @return string
     */
    public function obtainFormContent(string $html): string
    {
        $DOMDocument = new DOMDocument();
        @$DOMDocument->loadHTML($html);
        $forms = $DOMDocument->getElementsByTagName("form");
        $form = $forms->item(0);

        return $form->ownerDocument->saveHTML($form);
    }

    /**
     * @param string $html
     * @return PaymentForm
     */
    public function parseFormAttributes(string $html): PaymentForm
    {
        $DOMDocument = new DOMDocument();
        @$DOMDocument->loadHTML($html);
        $forms = $DOMDocument->getElementsByTagName("form");
        $form = $forms->item(0);
        $action = $form->getAttribute('action');
        $hiddenInputs = $this->buildHiddenInputs($DOMDocument);

        return new PaymentForm($action, $hiddenInputs);
    }

    /**
     * @param DOMDocument $DOMDocument
     * @return array
     */
    protected function buildHiddenInputs(DOMDocument $DOMDocument): array
    {
        $hiddenInputs = [];
        $xpath = new DOMXpath($DOMDocument);
        $inputs = $xpath->query('//input');
        foreach ($inputs as $input) {
            $nameAttribute = $input->getAttribute('name');
            $valueAttribute = $input->getAttribute('value');
            $hiddenInputs[$nameAttribute] = $valueAttribute;
        }

        return $hiddenInputs;
    }
}