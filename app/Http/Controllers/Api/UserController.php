<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        private readonly UserRepository $users,
        private readonly UserService $userService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $result = $this->users->datatable([
            ...$request->all(),
            'search' => $request->input('search.value'),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return response()->json([
            'draw' => (int) $request->input('draw'),
            'recordsTotal' => $result['recordsTotal'],
            'recordsFiltered' => $result['recordsFiltered'],
            'data' => UserResource::collection($result['users'])->resolve(),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        return new UserResource($this->userService->update($user, $request->validated()));
    }

    public function destroy(Request $request, User $user): JsonResponse
    {
        $this->userService->delete($user, $request->user('api'));

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
