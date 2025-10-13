<?php

namespace App\Services;
use App\Repositories\AuthRepository;

class AuthService
{
    protected $AuthRepo;

    public function __construct(AuthRepository $AuthRepo)
    {
        $this->AuthRepo = $AuthRepo;
    }

    public function register(array $data)
    {
        return $this->AuthRepo->register($data);
    }

    public function login(array $data)
    {
        return $this->AuthRepo->login($data);
    }

    public function logout()
    {
        return $this->AuthRepo->logout();
    }

    public function showActiveUser()
    {
        return $this->AuthRepo->getActiveUser();
    }









}
