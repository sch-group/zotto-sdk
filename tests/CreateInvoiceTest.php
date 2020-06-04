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
//        $link = $this->createInvoice();
//        print_r($link);
//        $this->assertTrue(is_string($link));
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
            Transaction::OPEN_BANKING_REDIRECT_TYPE
        );
        $this->expectException(ZottoCallMethodCallException::class);

        $link = $this->client->generatePaymentHtml($transaction);

    }

    public function testCreateInvoice()
    {

        $transaction = new Transaction(
            834417,
            19.99,
            'EUR',
            'http://euro.local/payments/zotto/return?order_number=834417',
            'http://b.euro.local/payments/zotto/result',
            'http://euro.local/payments/zotto/return?order_number=834417',
            'http://euro.local/payments/zotto/return?order_number=834417',
            'http://euro.local/payments/zotto/return?order_number=834417',
            834417,
            Transaction::CARD_REDIRECT_TYPE,
            "ainur_ahmetgalie@mail.ru"
        );

        $html = $this->client->generatePaymentHtml($transaction);

        $this->assertTrue(is_string($html));

    }
}