<?php

namespace App\Interfaces;
use Illuminate\Http\Request;
interface OrderRepositoryInterface
{
    public function register(Request $request);
    public function login(Request $request);
    public function update(Request $requestm,$id);
    public function logout();
}
