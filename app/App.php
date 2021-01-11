<?php


namespace app;


use app\console\PrizesConfigurationInit;
use app\console\PrizesConfigurationShow;
use app\console\SendMoneyPrizes;
use app\infrastructure\PrizeRepository;
use app\infrastructure\PrizesConfigurationRepository;
use app\model\command\CancelPrize;
use app\model\command\CancelPrizeHandler;
use app\model\command\RegisterPrize;
use app\model\command\RegisterPrizeHandler;
use app\model\command\SavePrizesConfiguration;
use app\model\command\SavePrizesConfigurationHandler;
use app\model\command\SetPrizesConfiguration;
use app\model\command\SetPrizesConfigurationHandler;
use app\model\PrizesConfiguration;
use PDO;
use Prooph\Common\Event\ProophActionEventEmitter;
use Prooph\Common\Messaging\FQCNMessageFactory;
use Prooph\EventStore\ActionEventEmitterEventStore;
use Prooph\EventStore\Pdo\MySqlEventStore;
use Prooph\EventStore\Pdo\PersistenceStrategy\MySqlAggregateStreamStrategy;
use Prooph\EventStoreBusBridge\EventPublisher;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\EventBus;
use Prooph\ServiceBus\Plugin\Router\CommandRouter;
use Prooph\SnapshotStore\Pdo\PdoSnapshotStore;
use Symfony\Component\Console\Application;


class App
{
    public static EventBus $eventBus;

    public static CommandBus $commandBus;

    private static ?PrizesConfiguration $prizesConfiguration;

    public static PrizesConfigurationRepository $prizesConfigurationRepository;

    public static PrizeRepository $prizeRepository;

    public static function prizesConfiguration(): PrizesConfiguration
    {
        if (!isset(self::$prizesConfiguration)) {
            self::$prizesConfiguration = self::$prizesConfigurationRepository->get('1');
        }
        return self::$prizesConfiguration;
    }

    private function requestPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'])['path'] ?? '';
    }

    public static function log($message)
    {
        $logfile = '../runtime/debug.log';
        $now = microtime(true);
        $nowMs = floor(($now - floor($now)) * 1000);
        $formattedMessage = date("[Y.d.m H:i:s.$nowMs]") . "\t" . $message . PHP_EOL;
        file_put_contents($logfile, $formattedMessage, FILE_APPEND);
    }

    private function config()
    {
        $this->initProoph();
    }

    public function runCli()
    {
        $this->config();
        // Is it a framework or a library? I'd say the latter ;-)
        $application = new Application();
        $application->add(new PrizesConfigurationInit());
        $application->add(new PrizesConfigurationShow());
        $application->add(new SendMoneyPrizes());
        $application->run();
    }

    public function run()
    {
        $this->config();

        (new Endpoints())->process($this->requestPath());

        exit();
    }

    public function initProoph()
    {
        // @todo if ever going to prod, move credentials to somewhere safe & add testing database
        $pdo = new PDO('mysql:dbname=root;host=mysql', 'root', 'root');
        $eventEmitter = new ProophActionEventEmitter();
        $eventStoreMysql = new MySqlEventStore(new FQCNMessageFactory(), $pdo, new MySqlAggregateStreamStrategy());
        $eventStore = new ActionEventEmitterEventStore($eventStoreMysql, $eventEmitter);

        self::$eventBus = new EventBus($eventEmitter);
        $eventPublisher = new EventPublisher(self::$eventBus);
        $eventPublisher->attachToEventStore($eventStore);

        $pdoSnapshotStore = new PdoSnapshotStore($pdo);
        self::$prizeRepository = new PrizeRepository($eventStore, $pdoSnapshotStore);
        self::$prizesConfigurationRepository = new PrizesConfigurationRepository($eventStore, $pdoSnapshotStore);

        self::$commandBus = new CommandBus();
        $router = new CommandRouter();

        $router->route(RegisterPrize::class)->to(new RegisterPrizeHandler(self::$prizeRepository));
        $router->route(CancelPrize::class)->to(new CancelPrizeHandler(self::$prizeRepository));

        $router->route(SetPrizesConfiguration::class)->to(new SetPrizesConfigurationHandler(self::$prizesConfigurationRepository));
        $router->route(SavePrizesConfiguration::class)->to(new SavePrizesConfigurationHandler(self::$prizesConfigurationRepository));
        $router->attachToMessageBus(self::$commandBus);

    }


}