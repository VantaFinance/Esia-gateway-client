<?php
/**
 * ESIA Gateway Client
 *
 * @author Valentin Nazarov <v.nazarov@pos-credit.ru>
 * @copyright Copyright (c) 2023, The Vanta
 */

declare(strict_types=1);

namespace Vanta\Integration\EsiaGateway\Struct;

use InvalidArgumentException;

final class CountryIso
{
    /**
     * @var non-empty-string
     */
    private string $value;

    /**
     * @param non-empty-string $value
     */
    public function __construct(
        string $value,
    ) {
        if (!preg_match('/^[a-zA-Z]{3}$/', $value)) {
            throw new InvalidArgumentException('Invalid country ISO code, must be an ISO 3166-1 alpha-3 code');
        }

        $this->value = $value;
    }

    /**
     * @return non-empty-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->value;
    }
}
