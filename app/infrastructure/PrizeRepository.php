<?php

namespace app\infrastructure;

use app\model\Prize;
use app\model\PrizeRepositoryInterface;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\EventStore;
use Prooph\SnapshotStore\SnapshotStore;

class PrizeRepository extends AggregateRepository implements PrizeRepositoryInterface
{

    public function __construct(EventStore $eventStore, SnapshotStore $snapshotStore)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(Prize::class),
            new AggregateTranslator(),
            $snapshotStore,
            null,
            true
        );
    }

    public function save(Prize $prize): void
    {
        $this->saveAggregateRoot($prize);
    }

    public function get(string $id): ?Prize
    {
        return $this->getAggregateRoot($id);
    }
}