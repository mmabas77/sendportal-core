<?php

namespace Sendportal\Base\Services\Subscribers;

use Exception;
use Illuminate\Support\Arr;
use Sendportal\Base\Models\Subscriber;
use Sendportal\Base\Repositories\Subscribers\SubscriberTenantRepositoryInterface;

class ImportSubscriberService
{
    /** @var SubscriberTenantRepositoryInterface */
    protected $subscribers;

    public function __construct(SubscriberTenantRepositoryInterface $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * @throws Exception
     */
    public function import(int $workspaceId, array $data): Subscriber
    {
        // Attempt to find the subscriber by ID or Email in a single query
        $subscriber = $this->subscribers->getNewInstance()->query()
            ->where('workspace_id', $workspaceId)
            ->when(!empty($data['id']), function ($query) use ($data) {
                $query->where('id', $data['id']);
            }, function ($query) use ($data) {
                $query->where('email', Arr::get($data, 'email'));
            })
            ->with('tags') // Eager load tags for later use
            ->first();

        if (! $subscriber) {
            // If subscriber not found, create a new one
            $subscriber = $this->subscribers->store(
                $workspaceId,
                Arr::except($data, ['id', 'tags'])
            );
        } else {
            // If found, merge and update tags
            $existingTags = $subscriber->tags->pluck('id')->toArray();
            $data['tags'] = array_merge($existingTags, Arr::get($data, 'tags', []));
            $data['tags'] = array_unique($data['tags']); // Ensure unique tags
        }

        // Update the subscriber (create or update tags, if applicable)
        $this->subscribers->update($workspaceId, $subscriber->id, $data);

        return $subscriber;
    }

}
