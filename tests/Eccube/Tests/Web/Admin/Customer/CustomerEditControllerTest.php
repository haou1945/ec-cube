<?php

namespace Eccube\Tests\Web\Admin\Customer;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;

class CustomerEditControllerTest extends AbstractAdminWebTestCase
{

    protected $Customer;

    public function setUp()
    {
        parent::setUp();
        $this->Customer = $this->createCustomer();
    }

    protected function createFormData()
    {
        $faker = $this->getFaker();
        $tel = explode('-', $faker->phoneNumber);

        $email = $faker->safeEmail;
        $password = $faker->lexify('????????');

        $form = array(
            'name' => array(
                'name01' => $faker->lastName,
                'name02' => $faker->firstName,
            ),
            'kana' => array(
                'kana01' => $faker->lastKanaName ,
                'kana02' => $faker->firstKanaName,
            ),
            'company_name' => $faker->company,
            'zip' => array(
                'zip01' => $faker->postcode1(),
                'zip02' => $faker->postcode2(),
            ),
            'address' => array(
                'pref' => '5',
                'addr01' => $faker->city,
                'addr02' => $faker->streetAddress,
            ),
            'tel' => array(
                'tel01' => $tel[0],
                'tel02' => $tel[1],
                'tel03' => $tel[2],
            ),
            'fax' => array(
                'fax01' => $tel[0],
                'fax02' => $tel[1],
                'fax03' => $tel[2],
            ),
            'email' => $email,
            'password' => array(
                'first' => $password,
                'second' => $password,
            ),
            'birth' => array(
                'year' => $faker->year,
                'month' => intval($faker->month),
                'day' => intval($faker->dayOfMonth),
            ),
            'sex' => 1,
            'job' => 1,
            'status' => 1,
            '_token' => 'dummy'
        );
        return $form;
    }

    public function testIndex()
    {
        $this->client->request(
            'GET',
            $this->app->path('admin_customer_edit', array('id' => $this->Customer->getId()))
        );
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }

    public function testIndexWithPost()
    {
        $form = $this->createFormData();
        $crawler = $this->client->request(
            'POST',
            $this->app->path('admin_customer_edit', array('id' => $this->Customer->getId())),
            array('admin_customer' => $form)
        );
        $this->assertTrue($this->client->getResponse()->isRedirect(
            $this->app->url(
                'admin_customer_edit',
                array('id' => $this->Customer->getId())
            )
        ));

        $EditedCustomer = $this->app['eccube.repository.customer']->find($this->Customer->getId());
        $this->expected = $form['email'];
        $this->actual = $EditedCustomer->getEmail();
        $this->verify();
    }


}
