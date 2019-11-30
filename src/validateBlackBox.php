#!/usr/bin/env php
<?php
error_reporting(0);
require_once "BlackBox.php";

$checks = [
  [
    '_GET' =>
      [
        'list' => 'customers',
        'page' => 2,
      ],
    'in' =>
      [
        'page',
        3,
      ],
    'out' => 'list=customers&page=3',
  ],
  [
    '_GET' =>
      [
        'list' => 'customers',
        'page' => 2,
      ],
    'in' =>
      [
        'page',
        2,
      ],
    'out' => 'list=customers',
  ],
  [
    '_GET' =>
      [
        'list' => 'customers',
        'page' => 2,
      ],
    'in' =>
      [
        [
          'list',
          'page',
        ],
        [
          'employees',
          null,
        ],
      ],
    'out' => 'list=employees',
  ],
  [
    '_GET' =>
      [
        'foo' => 1,
        'bar' => 2,
      ],
    'in' =>
      [
        'foo',
        2,
        [
          'baz' => 3,
        ],
      ],
    'out' => 'foo=2&bar=2&baz=3',
  ],
  [
    '_GET' =>
      [
        'foo' => 1,
        'bar' => 2,
      ],
    'in' =>
      [
        [
          'foo',
          'bar',
        ],
        [
          false,
          1,
        ],
      ],
    'out' => 'bar=1',
  ],
  [
    '_GET' =>
      [
        'foo' => 1,
        'bar' => 2,
      ],
    'in' =>
      [
        'baz',
      ],
    'out' => 'foo=1&bar=2',
  ],
];

$blackBox = new Blackbox\BlackBox();
$testsTotal = count($checks);
$testsOk = 0;

foreach ($checks as $check) {
    $_GET = $check['_GET'];
    $out = $blackBox->url(...$check['in']);
    $outParsed = parse_url($out);

    if ($outParsed['query'] === $check['out']) {
        $testsOk++;
    }
}

echo "{$testsOk} av {$testsTotal} valideringar OK\n";
