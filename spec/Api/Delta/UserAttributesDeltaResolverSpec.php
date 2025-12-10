<?php

declare(strict_types=1);

namespace spec\Lingoda\BrazeBundle\Api\Delta;

use Lingoda\BrazeBundle\Api\Delta\IdentifierResolver;
use Lingoda\BrazeBundle\Api\Delta\StorageInterface;
use Lingoda\BrazeBundle\Api\Delta\UserAttributesDeltaResolver;
use Lingoda\BrazeBundle\Api\Object\ExternalId;
use Lingoda\BrazeBundle\Api\Object\UserAttributes;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;

class UserAttributesDeltaResolverSpec extends ObjectBehavior
{
    function let(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $this->beConstructedWith($store, $identifierResolver, $logger);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UserAttributesDeltaResolver::class);
    }

    function it_stores_delta_attributes_when_no_previous_data_exists(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $externalId = new ExternalId('user-123');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
            'email' => 'test@example.com',
            'first_name' => 'John',
        ]);

        $identifierResolver->resolve($userAttributes)->willReturn('user-123');
        $store->read('user-123')->willReturn(null);

        // Values are encoded as SHA256 hashes
        $store->write('user-123', Argument::that(function ($options) {
            return isset($options['email'])
                && isset($options['first_name'])
                && isset($options['external_id'])
                && \count($options) === 3;
        }))->shouldBeCalledOnce();

        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->storeDeltaAttributes($userAttributes);
    }

    function it_merges_with_existing_data_when_storing_delta_attributes(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $externalId = new ExternalId('user-123');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
            'email' => 'new@example.com',
        ]);

        $storedFirstNameHash = hash('sha256', 'John');
        $identifierResolver->resolve($userAttributes)->willReturn('user-123');
        $store->read('user-123')->willReturn([
            'first_name' => $storedFirstNameHash,
            'email' => hash('sha256', 'old@example.com'),
        ]);

        // New email hash should overwrite old, first_name should be preserved
        $store->write('user-123', Argument::that(function ($options) use ($storedFirstNameHash) {
            return isset($options['email'])
                && isset($options['first_name'])
                && $options['first_name'] === $storedFirstNameHash
                && isset($options['external_id']);
        }))->shouldBeCalledOnce();

        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->storeDeltaAttributes($userAttributes);
    }

    function it_new_values_overwrite_existing_when_storing(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $externalId = new ExternalId('user-123');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
            'email' => 'new@example.com',
        ]);

        $storedFirstNameHash = hash('sha256', 'John');
        $storedEmailHash = hash('sha256', 'old@example.com');
        $newEmailHash = hash('sha256', 'new@example.com');
        $identifierResolver->resolve($userAttributes)->willReturn('user-123');
        $store->read('user-123')->willReturn([
            'first_name' => $storedFirstNameHash,
            'email' => $storedEmailHash,
        ]);

        // New values overwrite stored values (array_merge behavior)
        $store->write('user-123', Argument::that(function ($options) use ($newEmailHash, $storedFirstNameHash) {
            return isset($options['email'])
                && $options['email'] === $newEmailHash
                && isset($options['first_name'])
                && $options['first_name'] === $storedFirstNameHash;
        }))->shouldBeCalledOnce();

        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->storeDeltaAttributes($userAttributes);
    }

    function it_removes_delta_attributes_from_stored_data(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $externalId = new ExternalId('user-123');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
        ]);

        $identifierResolver->resolve($userAttributes)->willReturn('user-123');
        $store->read('user-123')->willReturn([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'test@example.com',
        ]);

        $store->write('user-123', Argument::that(function ($options) {
            return isset($options['last_name'])
                && $options['last_name'] === 'Doe'
                && !isset($options['first_name'])
                && !isset($options['email']);
        }))->shouldBeCalledOnce();

        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->removeDeltaAttributes($userAttributes, ['first_name', 'email']);
    }

    function it_does_not_write_when_no_stored_data_exists_for_removal(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $externalId = new ExternalId('user-123');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
        ]);

        $identifierResolver->resolve($userAttributes)->willReturn('user-123');
        $store->read('user-123')->willReturn(null);

        $store->write(Argument::cetera())->shouldNotBeCalled();
        $logger->info(Argument::cetera())->shouldNotBeCalled();

        $this->removeDeltaAttributes($userAttributes, ['first_name']);
    }

    function it_does_not_write_when_attributes_to_remove_is_empty(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $externalId = new ExternalId('user-123');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
        ]);

        $identifierResolver->resolve($userAttributes)->willReturn('user-123');
        $store->read('user-123')->willReturn([
            'first_name' => 'John',
        ]);

        $store->write(Argument::cetera())->shouldNotBeCalled();
        $logger->info(Argument::cetera())->shouldNotBeCalled();

        $this->removeDeltaAttributes($userAttributes, []);
    }

    function it_ignores_non_existing_attributes_when_removing(
        StorageInterface $store,
        IdentifierResolver $identifierResolver,
        LoggerInterface $logger
    ) {
        $externalId = new ExternalId('user-123');
        $userAttributes = new UserAttributes([
            'external_id' => $externalId,
        ]);

        $identifierResolver->resolve($userAttributes)->willReturn('user-123');
        $store->read('user-123')->willReturn([
            'first_name' => 'John',
        ]);

        $store->write('user-123', Argument::that(function ($options) {
            return !isset($options['first_name'])
                && empty($options);
        }))->shouldBeCalledOnce();

        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->removeDeltaAttributes($userAttributes, ['first_name', 'non_existing_attr']);
    }
}
