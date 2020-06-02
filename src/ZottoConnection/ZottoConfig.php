<?php

namespace SchGroup\Zotto\ZottoConnection;

class ZottoConfig
{

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $merchantKey;
    /**
     * @var string
     */
    private $secretKey;

    /**
     * WeldPayConfig constructor.
     * @param string $host
     * @param string $merchantKey
     * @param string $secretKey
     */
    public function __construct(string $host, string $merchantKey, string $secretKey)
    {
        $this->host = $host;
        $this->merchantKey = $merchantKey;
        $this->secretKey = $secretKey;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->merchantKey;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

}