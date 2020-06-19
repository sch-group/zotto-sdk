<?php


namespace SchGroup\Zotto\Tests;


use SchGroup\Zotto\Components\Transaction;
use SchGroup\Zotto\Exceptions\ZottoCallMethodCallException;

class CreateInvoiceTest extends InitTest
{
    /**
     * @throws \SchGroup\Zotto\Exceptions\ZottoCallMethodCallException
     */
    public function testCreateSuccess()
    {
        $successTransaction = $this->createSuccessTransaction();
        $link = $this->client->generatePaymentHtml($successTransaction);
        $this->assertTrue(is_string($link));
    }

    /**
     * @throws ZottoCallMethodCallException
     * @throws \SchGroup\Zotto\Exceptions\InvalidRedirectTypeException
     * @throws \SchGroup\Zotto\Exceptions\WrongFormParseException
     */
    public function testSuccessParsePaymentForm()
    {
        $successTransaction = $this->createSuccessTransaction();
        $form = $this->client->generatePaymentFormHtml($successTransaction);
        $this->assertTrue(is_string($form));
        $this->assertContains('form', $form);
    }

    /**
     *
     * @throws ZottoCallMethodCallException
     * @throws \SchGroup\Zotto\Exceptions\InvalidRedirectTypeException
     * @throws \SchGroup\Zotto\Exceptions\WrongFormParseException
     */
    public function testRetrievePaymentFormAttributes()
    {
        $successTransaction = $this->createSuccessTransaction();
        $form = $this->client->generatePaymentForm($successTransaction);
        $this->assertNotEmpty($form->getActionAttribute());
        $this->assertTrue(count($form->getHiddenInputs()) > 0);
    }


    /**
     * @throws ZottoCallMethodCallException
     */
    public function testFail()
    {
        $transaction = new Transaction(
            rand(0, 10000000) . "kek",
            100,
            'GBP',
            'https://www.google.com',
            'https://www.google.com/',
            'https://www.google.com/',
            'https://www.google.com/',
            'https://www.google.com/',
            rand(0, 10000000) . "kek",
            Transaction::BOTH_REDIRECT_TYPE
        );
        $this->expectException(ZottoCallMethodCallException::class);

        $link = $this->client->generatePaymentHtml($transaction);

    }

    /**
     * @return mixed
     * @throws \SchGroup\Zotto\Exceptions\ZottoCallMethodCallException
     */
    public function testBankOpening()
    {
        $transaction = new Transaction(
            rand(0, 10000000) . "kek",
            15,
            'EUR',
            'https://www.google.com?order_id=835088&amount=22.88&status=success',
            'https://www.google.com',
            'https://www.yandex.com',
            'https://test-edu.ru/',
            'https://www.google.com/',
            rand(0, 10000000) . "kek",
            Transaction::OPEN_BANKING_REDIRECT_TYPE
        );

        $html = $this->client->generatePaymentHtml($transaction);
        $this->assertTrue(is_string($html));
    }

    /**
     * @return mixed
     * @throws \SchGroup\Zotto\Exceptions\ZottoCallMethodCallException
     */
    public function testPaymentLinkBanking()
    {
        $transaction = new Transaction(
            rand(0, 10000000) . "kek",
            15,
            'EUR',
            'https://www.google.com?order_id=835088&amount=22.88&status=success',
            'https://www.google.com',
            'https://www.yandex.com',
            'https://test-edu.ru/',
            'https://www.google.com/',
            rand(0, 10000000) . "kek",
            Transaction::OPEN_BANKING_REDIRECT_TYPE
        );

        $link = $this->client->generatePaymentLink($transaction);
        $this->assertNotEmpty($link);
        $this->assertTrue(is_string($link));
    }

    /**
     * @return mixed
     * @throws \SchGroup\Zotto\Exceptions\ZottoCallMethodCallException
     */
    public function testPaymentLinkCard()
    {
        $transaction = new Transaction(
            rand(0, 10000000) . "kek",
            15,
            'EUR',
            'https://www.google.com?order_id=835088&amount=22.88&status=success',
            'https://www.google.com',
            'https://www.yandex.com',
            'https://test-edu.ru/',
            'https://www.google.com/',
            rand(0, 10000000) . "kek",
            Transaction::CARD_REDIRECT_TYPE
        );

        $link = $this->client->generatePaymentLink($transaction);
        $this->assertNotEmpty($link);
        $this->assertTrue(is_string($link));
    }
}