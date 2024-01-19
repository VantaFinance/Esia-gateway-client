<?php
/**
 * ESIA Gateway Client
 *
 * @author Valentin Nazarov <v.nazarov@pos-credit.ru>
 * @copyright Copyright (c) 2023, The Vanta
 */

declare(strict_types=1);

namespace Vanta\Integration\EsiaGateway\Struct;

use Webmozart\Assert\Assert;

final class RussianInternationalPassportSeries
{
    /**
     * @var numeric-string
     */
    public readonly string $value;

    /**
     * @param numeric-string $value
     */
    public function __construct(
        string $value,
    ) {
        Assert::regex($value, '/^\d{2}$/', 'Неверный формат серии документа, ожидается 2 цифры');

        $this->value = $value;
    }

    /**
     * @return numeric-string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
