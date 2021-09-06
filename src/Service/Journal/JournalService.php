<?php
declare(strict_types=1);

namespace App\Service\Journal;

use App\Service\Journal\Adapter\AdapterInterface;

class JournalService
{
    /** @var AdapterInterface */
    protected AdapterInterface $adapter;

    public function __construct(
        AdapterInterface $adapter
    )
    {
        $this->adapter = $adapter;
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter(): AdapterInterface
    {
        return $this->adapter;
    }
}