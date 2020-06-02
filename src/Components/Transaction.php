<?php


namespace SchGroup\Zotto\Components;


class Transaction
{
    const CARD_REDIRECT_TYPE = 1;

    const OPEN_BANKING_REDIRECT_TYPE = 2;

    const BOTH_REDIRECT_TYPE = 3;

    const AVAILABLE_REDIRECT_TYPES = [
        self::CARD_REDIRECT_TYPE,
        self::OPEN_BANKING_REDIRECT_TYPE,
        self::BOTH_REDIRECT_TYPE
    ];

    /**
     * @var string
     */
    private $orderNumber;

    /**
     * @var string
     */
    private $successUrl;

    /**
     * @var string
     */
    private $callbackUrl;
    /**
     * @var float
     */
    private $amount;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var string
     */
    private $backUrl;
    /**
     * @var string
     */
    private $errorUrl;
    /**
     * @var string
     */
    private $uniqueUserId;
    /**
     * @var string|null
     */
    private $email;
    /**
     * @var string|null
     */
    private $phone;
    /**
     * @var string
     */
    private $failedUrl;
    /**
     * @var int
     */
    private $redirectType;

    /**
     * Transaction constructor.
     * @param string $orderNumber
     * @param float $amount
     * @param string $currency
     * @param string $successUrl
     * @param string $callbackUrl
     * @param string $backUrl
     * @param string $failedUrl
     * @param string $errorUrl
     * @param string $uniqueUserId
     * @param int $redirectType
     * @param string|null $email
     * @param string|null $phone
     */
    public function __construct(
        string $orderNumber,
        float $amount,
        string $currency,
        string $successUrl,
        string $callbackUrl,
        string $backUrl,
        string $failedUrl,
        string $errorUrl,
        string $uniqueUserId,
        int $redirectType,
        string $email = null,
        string $phone = null
    )
    {
        $this->orderNumber = $orderNumber;
        $this->successUrl = $successUrl;
        $this->amount = $amount;
        $this->currency = $currency;
        $this->callbackUrl = $callbackUrl;
        $this->backUrl = $backUrl;
        $this->errorUrl = $errorUrl;
        $this->uniqueUserId = $uniqueUserId;
        $this->email = $email;
        $this->phone = $phone;
        $this->failedUrl = $failedUrl;
        $this->redirectType = in_array($redirectType, self::AVAILABLE_REDIRECT_TYPES)
            ? $redirectType : self::CARD_REDIRECT_TYPE;
    }

    /**
     * @return int
     */
    public function getRedirectType(): int
    {
        return $this->redirectType;
    }

    /**
     * @return string
     */
    public function getFailedUrl(): string
    {
        return $this->failedUrl;
    }

    /**
     * @return string
     */
    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }


    /**
     * @return string
     */
    public function getSuccessUrl(): string
    {
        return $this->successUrl;
    }

    /**
     * @return string
     */
    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return string
     */
    public function getBackUrl(): string
    {
        return $this->backUrl;
    }

    /**
     * @return string
     */
    public function getErrorUrl(): string
    {
        return $this->errorUrl;
    }

    /**
     * @return string
     */
    public function getUniqueUserId(): string
    {
        return $this->uniqueUserId;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $merchantKey
     * @param string $merchantSecret
     * @return string
     */
    public function getMacString(string $merchantKey, string $merchantSecret): string
    {
        $sentence =
            (string)$this->getAmount()
            . $this->getBackUrl()
            . $this->getCallbackUrl()
            . $this->getCurrency()
            . $this->getErrorUrl()
            . $this->getFailedUrl()
            . $merchantKey
            . $this->getOrderNumber()
            . $this->getSuccessUrl()
            . $this->getUniqueUserId()
            . $merchantSecret;

        return hash('sha256', $sentence);
    }

}