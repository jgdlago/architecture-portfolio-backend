<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Http\Resources\ContactResource;
use App\RepositoryInterfaces\ContactRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Exception;

class ContactController extends Controller
{
    protected ContactRepositoryInterface $contactRepository;
    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @return AnonymousResourceCollection
     */
    public function index(): AnonymousResourceCollection
    {
        return ContactResource::collection(
            $this->contactRepository->getAllModel(true, request('perPage', 10))
        );
    }

    /**
     * @param  ContactFormRequest $contactDetails
     * @return JsonResource
     * @throws Exception
     */
    public function store(ContactFormRequest $contactDetails): JsonResource
    {
        return new ContactResource(
            $this->contactRepository->createModel($contactDetails->safe()->toArray())
        );
    }

    /**
     * @param  int $contactId
     * @return JsonResource
     */
    public function show(int $contactId): JsonResource
    {
        return new ContactResource(
            $this->contactRepository->getModelById($contactId)
        );
    }

    /**
     * @param  ContactFormRequest $contactDetails
     * @param  int                $contactId
     * @return JsonResource
     */
    public function update(ContactFormRequest $contactDetails, int $contactId): JsonResource
    {
        return new ContactResource(
            $this->contactRepository->updateModel($contactDetails->safe()->toArray(), $contactId)
        );
    }

    /**
     * @param int $contactId
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(int $contactId): JsonResponse
    {
        return response()->json($this->contactRepository->deleteModel($contactId));
    }
}
