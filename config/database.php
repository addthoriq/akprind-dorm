<?php

function getDatabaseConfig(): array {
  return [
    "database" => [
      "test" => [
        "url" => "pgsql:host=localhost;port=5432;dbname=akprind_dorm",
        "username" => "kangpsql",
        "password" => null
      ],
      "prod" => [
        "url" => "pgsql:host=localhost;port=5432;dbname=akprind_dorm",
        "username" => "kangpsql",
        "password" => null
      ]
    ]
  ];
}
