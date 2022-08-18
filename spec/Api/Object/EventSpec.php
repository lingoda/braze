<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Api\Object;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Api\Object\Event;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use PhpSpec\ObjectBehavior;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class EventSpec extends ObjectBehavior
{
    function let()
    {
        CarbonImmutable::setTestNow(CarbonImmutable::now());

        $this->beConstructedWith([
            'external_id' => new ExternalId('external-id'),
            'name' => 'event-name',
        ]);
    }

    function letGo()
    {
        CarbonImmutable::setTestNow();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Event::class);
        $this->getOptions()->shouldHaveKey('external_id');
        $this->getOptions()->shouldHaveKey('name');
        $this->getOptions()->shouldHaveKeyWithDate('time', CarbonImmutable::now());
    }

    function it_throws_exception_on_missing_mandatory_fields()
    {
        $this->beConstructedWith(['external_id' => new ExternalId('external-id')]);
        $this
            ->shouldThrow(new MissingOptionsException('The required option "name" is missing.'))
            ->duringInstantiation()
        ;
    }

    function it_can_set_time()
    {
        $time = CarbonImmutable::now()->addDay();

        $this->beConstructedWith([
            'external_id' => new ExternalId('external-id'),
            'name' => 'event-name',
            'time' => $time,
        ]);

        $this->getOptions()->shouldHaveKeyWithDate('time', $time);
    }

    function it_throws_exception_on_empty_event_name()
    {
        $this->beConstructedWith([
            'external_id' => new ExternalId('external-id'),
            'name' => '',
        ]);
        $this
            ->shouldThrow(new InvalidOptionsException('The option "name" with value "" is invalid.'))
            ->duringInstantiation()
        ;
    }

    public function getMatchers(): array
    {
        return [
            'haveKeyWithDate' => static function ($subject, $key, $value): bool {
                if (!\array_key_exists($key, $subject)) {
                    return false;
                }

                $currentValue = $subject[$key];

                return $currentValue->equalTo($value);
            },
        ];
    }
}
