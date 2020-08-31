Russia Phone Parser
===================

Detect region and phone type by phone number.

Install
-------

    composer require gupalo/ru-phone-parser

Use
---

    $phone = RuPhone::create('3013042350');
    print_r([
        'source' => $phone->getSource(), // '+7(301)304-23-50',
        'number' => $phone->getNumber(), // '3013042350',
        'code' => $phone->getCode(), // 301,
        'range_begin' => $phone->getRangeBegin(), // 3042300,
        'range_end' => $phone->getRangeEnd(), // 3042399,
        'capacity' => $phone->getCapacity(), // 100,
        'operator' => $phone->getOperator(), // 'ПАО "Ростелеком"',
        'city' => $phone->getCity(), // 'г. Северобайкальск',
        'region' => $phone->getRegion(), // 'Республика Бурятия',
    ]);
    print_r($phone->jsonSerialize()); // same

Invalid and not found phones throw Exceptions. See `tests`.


Links
-----

* `data` files: https://rossvyaz.gov.ru/deyatelnost/resurs-numeracii/vypiska-iz-reestra-sistemy-i-plana-numeracii
  (last updated 2020-08-17)
* https://habr.com/ru/company/hflabs/blog/489074/
