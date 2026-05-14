<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRolesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('access-admin') ?? false;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'role_ids' => ['present', 'array'],
            'role_ids.*' => ['integer', 'exists:roles,id'],
        ];
    }

    /**
     * @return array<int, int>
     */
    public function requestedRoleIds(): array
    {
        /** @var array<int, int|string> $ids */
        $ids = $this->input('role_ids', []);

        return array_values(array_unique(array_map('intval', $ids)));
    }

    public function triesToRemoveOwnAdminAccess(): bool
    {
        $targetUser = $this->route('user');

        if ($targetUser === null || ! $targetUser->is($this->user())) {
            return false;
        }

        return ! in_array(UserRole::Admin->value, $this->requestedRoleIds(), strict: true);
    }
}
