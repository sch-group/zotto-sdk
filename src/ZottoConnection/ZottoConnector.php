<?php

namespace SchGroup\Zotto\ZottoConnection;

use GuzzleHttp\Client;

use SchGroup\Zotto\Helpers\FormParser;
use SchGroup\Zotto\Components\{PaymentForm, Transaction};
use SchGroup\Zotto\Exceptions\{WrongFormParseException, ZottoCallMethodCallException, InvalidRedirectTypeException};

class ZottoConnector
{
    const PAYMENT_LINK_URI = "/api/v1/checkoutlink/intermediate/";

    const CREATE_PAYMENT_HTML = "/api/v1/checkoutpay/payment";
    /**
     * @var Client
     */
    private $client;

    /**
     * @var ZottoConfig
     */
    private $config;

    /**
     * @var FormParser
     */
    private $formParser;

    /**
     * ZottoConnector constructor.
     * @param ZottoConfig $config
     */
    public function __construct(ZottoConfig $config)
    {
        $this->config = $config;
        $this->client = new Client();
        $this->formParser = new FormParser();
    }

    /**
     * @param Transaction $transaction
     * @return mixed
     * @throws ZottoCallMethodCallException
     */
    public function generatePaymentHtml(Transaction $transaction): string
    {
        $generateTransactionUrl = $this->generateTransactionHtmlUrl();

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

            return $content;

        } catch (\Exception $exception) {
            throw new ZottoCallMethodCallException($generateTransactionUrl, $exception->getMessage());
        }

    }

    /**
     * @param Transaction $transaction
     * @return string
     * @throws ZottoCallMethodCallException
     * @throws InvalidRedirectTypeException
     * @throws WrongFormParseException
     */
    public function generatePaymentFormHtml(Transaction $transaction): string
    {
        $this->checkRedirectType($transaction);

        $paymentHtml = $this->generatePaymentHtml($transaction);

        $paymentForm = $this->formParser->obtainFormContent($paymentHtml);

        $this->checkParseForm($paymentForm);

        return $paymentForm;
    }

    /**
     * @param Transaction $transaction
     * @return PaymentForm
     * @throws ZottoCallMethodCallException
     * @throws InvalidRedirectTypeException
     * @throws WrongFormParseException
     */
    public function generatePaymentForm(Transaction $transaction): PaymentForm
    {
        $this->checkRedirectType($transaction);

        $paymentHtml = $this->generatePaymentHtml($transaction);

        $paymentForm = $this->formParser->parseFormAttributes($paymentHtml);

        $this->checkParseForm($paymentForm);

        return $paymentForm;
    }

    /**
     * @param Transaction $transaction
     * @return string|null
     * @throws ZottoCallMethodCallException
     */
    public function generatePaymentLink(Transaction $transaction): ?string
    {
        $this->generatePaymentHtml($transaction);

        $paymentLink = $this->config->getHost() . self::PAYMENT_LINK_URI . $transaction->getOrderNumber();

        $params = http_build_query([
            'ukey' => $transaction->getUniqueUserId(),
            'redirect_type' => $transaction->getRedirectType()
        ]);

        return $paymentLink . "?" . $params;
    }

    /**
     * @return string
     */
    private function generateTransactionHtmlUrl(): string
    {
        return $this->config->getHost() . self::CREATE_PAYMENT_HTML;
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

    /**
     * @param Transaction $transaction
     * @throws InvalidRedirectTypeException
     */
    protected function checkRedirectType(Transaction $transaction): void
    {
        if (!$transaction->isCardRedirectType()) {
            throw new InvalidRedirectTypeException();
        }
    }

    /**
     * @param string $paymentForm
     * @throws WrongFormParseException
     */
    protected function checkParseForm($paymentForm): void
    {
        if (empty($paymentForm)) {
            throw new WrongFormParseException();
        }
    }

}