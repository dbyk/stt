<?php

namespace app\infrastructure;

use app\model\PrizesConfiguration;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use Prooph\SnapshotStore\SnapshotStore;

class PrizesConfigurationRepository extends AggregateRepository
{

    public function __construct(EventStore $eventStore, SnapshotStore $snapshotStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(PrizesConfiguration::class),
            new AggregateTranslator(),
            $snapshotStore,
            null,
            true
        );
    }

    public function save(PrizesConfiguration $prize): void
    {
        $this->saveAggregateRoot($prize);
    }

    public function get(string $id): ?PrizesConfiguration
    {
        return $this->getAggregateRoot($id);
    }

}