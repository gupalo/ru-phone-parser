<?php

namespace Gupalo\RuPhoneParser;

class RuPhoneParser
{
    private static bool $isLoaded = false;

    // first 3 digits => [from, to, "capacity;operator;city|region"]
    private static array $phones3 = [];
    private static array $phones4 = [];
    private static array $phones8 = [];
    private static array $phones9 = [];

    /**
     * @param string $phoneNumber
     * @return RuPhone
     * @throws RuPhoneInvalidNumberException|RuPhoneNotFoundException
     */
    public static function parse(string $phoneNumber): RuPhone
    {
        self::load();

        $normalizedPhoneNumber = self::normalize($phoneNumber);
        $firstDigit = $normalizedPhoneNumber[0] ?? '';
        $threeFirstDigits = substr($normalizedPhoneNumber, 0, 3);
        $items = null;
        switch ($firstDigit) {
            case '3':
                $items = self::$phones3[$threeFirstDigits] ?? null;
                break;
            case '4':
                $items = self::$phones4[$threeFirstDigits] ?? null;
                break;
            case '8':
                $items = self::$phones8[$threeFirstDigits] ?? null;
                break;
            case '9':
                $items = self::$phones9[$threeFirstDigits] ?? null;
                break;
            default:
                throw new RuPhoneInvalidNumberException('invalid_first_digit');
        }
        if (!$items) {
            throw new RuPhoneInvalidNumberException('invalid_range');
        }
        $nextSevenDigits = substr($normalizedPhoneNumber, 3, 7);
        if (strlen($nextSevenDigits) !== 7) {
            throw new RuPhoneInvalidNumberException('wrong_length');
        }
        $numberPart = (int)$nextSevenDigits;

        foreach ($items as $item) {
            if ($numberPart >= $item[0] && $numberPart <= $item[1]) {
                [$capacity, $operator, $cityRegion] = explode(';', $item[2], 3);
                $cityRegionArray = explode('|', $cityRegion, 2);

                return new RuPhone(
                    $phoneNumber,
                    $normalizedPhoneNumber,
                    (int)$threeFirstDigits,
                    $item[0],
                    $item[1],
                    (int)$capacity,
                    $operator,
                    $cityRegionArray[0] ?? '',
                    $cityRegionArray[1] ?? '',
                );
            }
        }

        throw new RuPhoneNotFoundException('');
    }

    private static function load(): void
    {
        if (self::$isLoaded) {
            return;
        }

        $dir = dirname(__DIR__) . '/data';
        self::$phones3 = self::loadFile($dir . '/ABC-3xx.csv');
        self::$phones4 = self::loadFile($dir . '/ABC-4xx.csv');
        self::$phones8 = self::loadFile($dir . '/ABC-8xx.csv');
        self::$phones9 = self::loadFile($dir . '/DEF-9xx.csv');

        self::$isLoaded = true;
    }

    private static function loadFile(string $filename): array
    {
        $rows = explode("\n", file_get_contents($filename));
        if (!$rows) {
            return [];
        }

        $result = [];
        $isFirst = true;
        foreach ($rows as $row) {
            if ($isFirst) {
                $isFirst = false;
                continue;
            }
            $cols = explode(';', $row, 4);

            $result[$cols[0]][] = [
                (int)$cols[1],
                (int)$cols[2],
                (string)$cols[3],
            ];
        }

        return $result;
    }

    private static function normalize(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('#[^\d]+#', '', $phoneNumber);
        if (($phoneNumber[0] ?? '') === '7') {
            $phoneNumber = substr($phoneNumber, 1);
        }

        return $phoneNumber;
    }
}
