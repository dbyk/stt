Test task
------

Installation
```bash
composer install
docker-compose up -d
docker-compose exec php php ./cli app:prizes-config-init
```

Running tests
```bash
docker-compose exec php php vendor/bin/codecept run unit --coverage
```

Benchmark tests were done by bombardier
```bash
bombardier -k -c 200 -n 10000 -m GET -p r http://api.stt.localhost/ping
Statistics        Avg      Stdev        Max
  Reqs/sec       239.05     122.92    5370.80
  Latency      836.65ms   116.93ms      1.13s
  HTTP codes:
    1xx - 0, 2xx - 10000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:    59.38KB/s
```
```bash
bombardier -k -p r -c 200 -n 10000 -m POST -b "email=test@test.test" http://api.stt.localhost/get-prize
Statistics        Avg      Stdev        Max
  Reqs/sec       187.93      48.96     301.36
  Latency         1.05s   107.58ms      1.32s
  HTTP codes:
    1xx - 0, 2xx - 10000, 3xx - 0, 4xx - 0, 5xx - 0
    others - 0
  Throughput:    84.39KB/s
```

For educational reasons CQSR library [Prooph](http://getprooph.org/) was used.

There's no frontend app. 