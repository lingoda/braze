<?php

declare(strict_types = 1);

namespace spec\Lingoda\BrazeBundle\Serializer\Normalizer;

use Carbon\CarbonImmutable;
use Lingoda\BrazeBundle\Serializer\Normalizer\CarbonNormalizer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;

class CarbonNormalizerSpec extends ObjectBehavior
{
    function let()
    {
        CarbonImmutable::setTestNow('2021-08-10 09:00:00');
    }

    function letGo()
    {
        CarbonImmutable::setTestNow();
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CarbonNormalizer::class);
    }

    function it_supports_carbon_instances_together_with_context_option_only()
    {
        $carbon = CarbonImmutable::now();

        $this->supportsNormalization($carbon)->shouldBeEqualTo(false);
        $this->supportsNormalization($carbon, null, [CarbonNormalizer::BRAZE_DATETIME_FORMAT => 'Y-m-d'])->shouldBeEqualTo(true);
        $this->supportsNormalization($carbon, 'json', [CarbonNormalizer::BRAZE_DATETIME_FORMAT => 'Y-m-d'])->shouldBeEqualTo(true);
    }

    function it_throws_exception_with_invalid_input()
    {
        $this
            ->shouldThrow(new InvalidArgumentException('The object must be an instance of Carbon\CarbonInterface class.'))
            ->during('normalize', [new \stdClass()])
        ;
    }

    function it_throws_exception_when_format_is_missing()
    {
        $this
            ->shouldThrow(new LogicException('\'braze_datetime_format\' context option is missing or not a date time format.'))
            ->during('normalize', [CarbonImmutable::now()])
        ;
    }

    function it_can_normalize()
    {
        $this->normalize(CarbonImmutable::now(), 'json', [
            CarbonNormalizer::BRAZE_DATETIME_FORMAT => CarbonImmutable::ATOM,
        ])->shouldBeEqualTo('2021-08-10T09:00:00+00:00');
    }
}
