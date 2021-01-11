CREATE TABLE `event_streams`
(
    `no`               BIGINT(20)   NOT NULL AUTO_INCREMENT,
    `real_stream_name` VARCHAR(150) NOT NULL,
    `stream_name`      CHAR(41)     NOT NULL,
    `metadata`         JSON,
    `category`         VARCHAR(150),
    PRIMARY KEY (`no`),
    UNIQUE KEY `ix_rsn` (`real_stream_name`),
    KEY `ix_cat` (`category`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;

DROP TABLE IF EXISTS `projections`;
CREATE TABLE `projections`
(
    `no`           bigint(20)                    NOT NULL AUTO_INCREMENT,
    `name`         varchar(150) COLLATE utf8_bin NOT NULL,
    `position`     json                      DEFAULT NULL,
    `state`        json                      DEFAULT NULL,
    `status`       varchar(28) COLLATE utf8_bin  NOT NULL,
    `locked_until` char(26) COLLATE utf8_bin DEFAULT NULL,
    PRIMARY KEY (`no`),
    UNIQUE KEY `ix_name` (`name`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;


DROP TABLE IF EXISTS `snapshots`;
CREATE TABLE `snapshots`
(
    `aggregate_id`   varchar(150) COLLATE utf8_bin NOT NULL,
    `aggregate_type` varchar(150) COLLATE utf8_bin NOT NULL,
    `last_version`   int(11)                       NOT NULL,
    `created_at`     char(26) COLLATE utf8_bin     NOT NULL,
    `aggregate_root` blob,
    UNIQUE KEY `ix_aggregate_id` (`aggregate_id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8
  COLLATE = utf8_bin;
