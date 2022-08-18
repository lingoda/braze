<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Object\AppId;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\Purchase;
use PhpSpec\ObjectBehavior;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class PurchaseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'external_id' => new ExternalId('external-id'),
            'app_id' => new AppId('f995d9aa-2be4-4f28-8b21-5ba0d03d85a8'),
            'product_id' => 'sample-product-id',
            'currency' => 'EUR',
            'price' => 10.00,
            'time' => CarbonImmutable::now(),
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Purchase::class);
        $this->getOptions()->shouldHaveKey('external_id');
        $this->getOptions()->shouldHaveKey('app_id');
        $this->getOptions()->shouldHaveKey('product_id');
        $this->getOptions()->shouldHaveKey('currency');
        $this->getOptions()->shouldHaveKey('price');
        $this->getOptions()->shouldHaveKey('time');
    }

    function it_throws_exception_on_missing_mandatory_fields()
    {
        $this->beConstructedWith(['external_id' => new ExternalId('external-id')]);
        $this
            ->shouldThrow(new MissingOptionsException('The required options "app_id", "currency", "price", "product_id" are missing.'))
            ->duringInstantiation()
        ;
    }
}
