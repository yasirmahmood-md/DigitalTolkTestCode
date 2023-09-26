<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;


use DTApi\Repository\UserRepository;
use DTApi\Models\User;

class UserRepositoryTest extends TestCase
{
    /**
     * Test the has() method in Room class
     *
     * @return void
     */
    public function test_userrepository_createOrUpdate()
    {
        $user = new User();

        $user_repo = new UserRepository($user);

        $this->assertContains($user, $user_repo->createOrUpdate(null, ['name' => 'Yasir', 'email' => 'yasirrajpt@gmail.com', 'phone' => '03331334862', 'role' => 'admin', 'company_id' => 1, 'department_id' => 1, 'dob_or_orgid' => '1970-01-01', 'password' => 'password']));
    }
}