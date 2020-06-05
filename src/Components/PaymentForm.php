<?php


namespace SchGroup\Zotto\Components;


class PaymentForm
{
    /**
     * @var string
     */
    private $actionAttribute;

    /**
     * @var array
     */
    private $hiddenInputs;

    /**
     * PaymentForm constructor.
     * @param string $actionAttribute
     * @param array $hiddenInputs
     */
    public function __construct(string $actionAttribute, array $hiddenInputs)
    {
        $this->actionAttribute = $actionAttribute;
        $this->hiddenInputs = $hiddenInputs;
    }

    /**
     * @return string
     */
    public function getActionAttribute(): string
    {
        return $this->actionAttribute;
    }

    /**
     * @return array
     */
    public function getHiddenInputs(): array
    {
        return $this->hiddenInputs;
    }

}