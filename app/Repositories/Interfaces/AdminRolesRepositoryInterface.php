<?php


namespace App\Repositories\Interfaces;


interface AdminRolesRepositoryInterface
{
    public function getRoles(int $id = null);
    public function createRole(string $name, array $actions);
    public function changeRole(int $id, string $name, array $actions);
    public function deleteRole(int $id);
}
