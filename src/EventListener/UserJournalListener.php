<?php
namespace App\EventListener;

use App\Dto\Journal\JournalDto;
use App\Entity\User;
use App\Service\Journal\JournalService;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;

/**
 * Косячная реализация, но логика понятна, я думаю
 */
class UserJournalListener
{
    private JournalService $journalService;
    private array $journals = [];

    /**
     * @param JournalService $journalService
     */
    public function __construct(JournalService $journalService)
    {
        $this->journalService = $journalService;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(User $user, LifecycleEventArgs $args): void
    {
        $changed = [];

        foreach ($args->getEntityChangeSet() as $key => $field) {

            if ($field[0] !== $field[1]) {
                $changed[] = [
                    'field' => $key,
                    'old' => $field[0],
                    'new' => $field[1]
                ];
            }
        }

        if ($changed) {
            $journal = new JournalDto();
            $journal->setId($user->getId());
            $journal->setContent($changed);
            $journal->setType('userAction');

            $this->journals[] = $journal;
        }
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args): void
    {
        if (! empty($this->journals)) {
            $this->journalService->getAdapter()->store($this->journals);
        }
    }
}