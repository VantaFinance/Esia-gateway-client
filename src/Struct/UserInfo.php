<?php

declare(strict_types=1);

namespace Vanta\Integration\EsiaGateway\Struct;

use Brick\PhoneNumber\PhoneNumber;
use DateTimeImmutable;

final class UserInfo
{
    public function __construct(
        public readonly string $uid,
        public readonly string $firstName,
        public readonly string $middleName,
        public readonly string $lastName,
        public readonly DateTimeImmutable $birthDate,
        public readonly Gender $gender,
        public readonly bool $trusted,
        public readonly string $email,
        public readonly PhoneNumber $mobilePhone,
        public readonly CountryIso $citizenship,
        public readonly SnilsNumber $snils,
        public readonly InnNumber $inn,
        public readonly Address $registrationAddress,
        public readonly Address $homeAddress,
    ) {
    }
}
