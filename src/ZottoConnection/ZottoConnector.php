<?php

namespace SchGroup\Zotto\ZottoConnection;

use GuzzleHttp\Client;
use SchGroup\Zotto\Components\Transaction;
use SchGroup\Zotto\Exceptions\ZottoCallMethodCallException;

class ZottoConnector
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var ZottoConfig
     */
    private $config;

    /**
     * ZottoConnector constructor.
     * @param ZottoConfig $config
     */
    public function __construct(ZottoConfig $config)
    {
        $this->client = new Client();
        $this->config = $config;
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     * @throws ZottoCallMethodCallException
     */
    public function generatePaymentHtml(Transaction $transaction): string
    {
        $generateTransactionUrl = $this->config->getHost() . '/api/v1/checkoutpay/payment';

        try {
            $body = [
                'merchant_key' => $this->config->getMerchantKey(),
                'order_id' => $transaction->getOrderNumber(),
                'amount' => $transaction->getAmount(),
                'currency' => $transaction->getCurrency(),
                'success_url' => $transaction->getSuccessUrl(),
                'callback_url' => $transaction->getCallbackUrl(),
                'back_url' => $transaction->getBackUrl(),
                'failed_url' => $transaction->getFailedUrl(),
                'error_url' => $transaction->getErrorUrl(),
                'user_id' => $transaction->getUniqueUserId(),
                'redirect_type' => $transaction->getRedirectType(),
                'email' => $transaction->getEmail(),
                'phone' => $transaction->getPhone(),
                'mac_string' => $transaction->getMacString($this->config->getMerchantKey(), $this->config->getSecretKey()),
            ];

            $request = $this->client->post($generateTransactionUrl, [
                'form_params' => $body
            ]);
            $content = $request->getBody()->getContents();

            $this->checkException($content, $generateTransactionUrl);

            return $request->getBody()->getContents();

        } catch (\Exception $exception) {
            throw new ZottoCallMethodCallException($generateTransactionUrl, $exception->getMessage());
        }

    }

    /**
     * @param string $content
     * @param string $url
     * @throws ZottoCallMethodCallException
     */
    private function checkException(string $content, string $url): void
    {
        $content = json_decode($content, true);
        if (!empty($content['message'])) {
            throw new ZottoCallMethodCallException($url, $content['message']);
        }
    }

}