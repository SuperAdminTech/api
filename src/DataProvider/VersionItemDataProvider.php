<?php


namespace App\DataProvider;


use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\Version;

class VersionItemDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface {

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return Version::class === $resourceClass;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?Version {
        return new Version(`git describe --tags`);
    }

}