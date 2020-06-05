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
}