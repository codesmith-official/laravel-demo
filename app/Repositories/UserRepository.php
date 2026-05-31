<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class UserRepository
{
    public function findByEmail(string $email): ?User
    {
        return User::query()->where('email', $email)->first();
    }

    public function activeCount(): int
    {
        return User::query()->where('status', 'active')->count();
    }

    public function datatable(array $filters): array
    {
        $columns = [
            0 => 'id',
            1 => 'first_name',
            2 => 'last_name',
            3 => 'email',
            4 => 'phone_number',
            5 => 'last_login_at',
            6 => 'status',
        ];

        $baseQuery = User::query();
        $recordsTotal = (clone $baseQuery)->count();

        $query = $baseQuery
            ->when($filters['search'] ?? null, function (Builder $query, string $search): void {
                $query->where(function (Builder $query) use ($search): void {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%");
                });
            });

        $recordsFiltered = (clone $query)->count();
        $orderColumnIndex = (int) data_get($filters, 'order.0.column', 0);
        $orderDirection = data_get($filters, 'order.0.dir') === 'desc' ? 'desc' : 'asc';
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';
        $length = min(max((int) ($filters['length'] ?? 10), 1), 10);
        $start = max((int) ($filters['start'] ?? 0), 0);

        return [
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'users' => $query
                ->orderBy($orderColumn, $orderDirection)
                ->offset($start)
                ->limit($length)
                ->get(),
        ];
    }

    public function paginated(int $perPage = 10): LengthAwarePaginator
    {
        return User::query()->latest()->paginate($perPage);
    }
}
