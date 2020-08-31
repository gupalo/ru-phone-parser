<?php

namespace Gupalo\RuPhoneParser;

use JsonSerializable;

class RuPhone implements JsonSerializable
{
    private string $source;
    private string $number;
    private int $code;
    private int $rangeBegin;
    private int $rangeEnd;
    private int $capacity;
    private string $operator;
    private string $city;
    private string $region;

    public function __construct(
        string $source,
        string $number,
        int $code,
        int $rangeBegin,
        int $rangeEnd,
        int $capacity,
        string $operator,
        string $city,
        string $region
    ) {
        $this->source = $source;
        $this->number = $number;
        $this->code = $code;
        $this->rangeBegin = $rangeBegin;
        $this->rangeEnd = $rangeEnd;
        $this->capacity = $capacity;
        $this->operator = $operator;
        $this->city = $city;
        $this->region = $region;
    }

    public function jsonSerialize(): array
    {
        return [
            'source' => $this->source,
            'number' => $this->number,
            'code' => $this->code,
            'range_begin' => $this->rangeBegin,
            'range_end' => $this->rangeEnd,
            'capacity' => $this->capacity,
            'operator' => $this->operator,
            'city' => $this->city,
            'region' => $this->region,
        ];
    }

    /**
     * @param string $phoneNumber
     * @return static
     * @throws RuPhoneInvalidNumberException
     * @throws RuPhoneNotFoundException
     */
    public static function create(string $phoneNumber): self
    {
        return RuPhoneParser::parse($phoneNumber);
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function getRangeBegin(): int
    {
        return $this->rangeBegin;
    }

    public function getRangeEnd(): int
    {
        return $this->rangeEnd;
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getRegion(): string
    {
        return $this->region;
    }
}
