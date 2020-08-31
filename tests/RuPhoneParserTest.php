<?php

/** @noinspection PhpUnhandledExceptionInspection */

namespace Gupalo\RuPhoneParser\Tests;

use Gupalo\RuPhoneParser\RuPhone;
use Gupalo\RuPhoneParser\RuPhoneInvalidNumberException;
use Gupalo\RuPhoneParser\RuPhoneNotFoundException;
use PHPUnit\Framework\TestCase;

class RuPhoneParserTest extends TestCase
{
    public function testParse(): void
    {
        $data = [
            'source' => '3013042350',
            'number' => '3013042350',
            'code' => 301,
            'range_begin' => 3042300,
            'range_end' => 3042399,
            'capacity' => 100,
            'operator' => 'ПАО "Ростелеком"',
            'city' => 'г. Северобайкальск',
            'region' => 'Республика Бурятия',
        ];

        $phone = RuPhone::create('3013042350');
        self::assertSame($data, $phone->jsonSerialize());

        self::assertSame($data['source'], $phone->getSource());
        self::assertSame($data['number'], $phone->getNumber());
        self::assertSame($data['code'], $phone->getCode());
        self::assertSame($data['range_begin'], $phone->getRangeBegin());
        self::assertSame($data['range_end'], $phone->getRangeEnd());
        self::assertSame($data['capacity'], $phone->getCapacity());
        self::assertSame($data['operator'], $phone->getOperator());
        self::assertSame($data['city'], $phone->getCity());
        self::assertSame($data['region'], $phone->getRegion());
    }

    public function testParse4(): void
    {
        $data = [
            'source' => '4012300050',
            'number' => '4012300050',
            'code' => 401,
            'range_begin' => 2300000,
            'range_end' => 2309999,
            'capacity' => 10000,
            'operator' => 'ПАО "Вымпел-Коммуникации"',
            'city' => 'г. Калининград',
            'region' => 'Калининградская обл.',
        ];

        $phone = RuPhone::create('4012300050');
        self::assertSame($data, $phone->jsonSerialize());
    }

    public function testParse8(): void
    {
        $data = [
            'source' => '8126298050',
            'number' => '8126298050',
            'code' => 812,
            'range_begin' => 6298000,
            'range_end' => 6298499,
            'capacity' => 500,
            'operator' => 'ООО "ПИН"',
            'city' => 'г. Санкт-Петербург',
            'region' => '',
        ];

        $phone = RuPhone::create('8126298050');
        self::assertSame($data, $phone->jsonSerialize());
    }

    public function testParse9(): void
    {
        $data = [
            'source' => '9009740050',
            'number' => '9009740050',
            'code' => 900,
            'range_begin' => 9740000,
            'range_end' => 9839999,
            'capacity' => 100000,
            'operator' => 'ООО "Т2 Мобайл"',
            'city' => 'Республика Коми',
            'region' => '',
        ];

        $phone = RuPhone::create('9009740050');
        self::assertSame($data, $phone->jsonSerialize());
    }

    public function testParseExtraChars(): void
    {
        $data = [
            'source' => '+7(301) 3 0 4-235*0111111111',
            'number' => '3013042350111111111',
            'code' => 301,
            'range_begin' => 3042300,
            'range_end' => 3042399,
            'capacity' => 100,
            'operator' => 'ПАО "Ростелеком"',
            'city' => 'г. Северобайкальск',
            'region' => 'Республика Бурятия',
        ];

        $phone = RuPhone::create('+7(301) 3 0 4-235*0111111111');
        self::assertSame($data, $phone->jsonSerialize());
    }

    public function testParseInvalidFirstDigit(): void
    {
        $this->expectException(RuPhoneInvalidNumberException::class);
        $this->expectExceptionMessage('invalid_first_digit');

        RuPhone::create('03013042350');
    }

    public function testParseInvalidRange(): void
    {
        $this->expectException(RuPhoneInvalidNumberException::class);
        $this->expectExceptionMessage('invalid_range');

        RuPhone::create('3000000001');
    }

    public function testParseWrongLength(): void
    {
        $this->expectException(RuPhoneInvalidNumberException::class);
        $this->expectExceptionMessage('wrong_length');

        RuPhone::create('301123');
    }

    public function testParseNotFound(): void
    {
        $this->expectException(RuPhoneNotFoundException::class);
        $this->expectExceptionMessage('');

        RuPhone::create('3010000001');
    }
}
