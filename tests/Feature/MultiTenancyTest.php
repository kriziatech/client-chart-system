<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_clients_are_scoped_to_current_company()
    {
        // 1. Create two companies
        $companyA = Company::create(['name' => 'Company A']);
        $companyB = Company::create(['name' => 'Company B']);

        // 2. Create users for each company
        $userA = User::factory()->create(['company_id' => $companyA->id, 'email' => 'userA@example.com']);
        $userB = User::factory()->create(['company_id' => $companyB->id, 'email' => 'userB@example.com']);

        // 3. Login as User A and create a client
        $this->actingAs($userA);
        $clientA = Client::create([
            'first_name' => 'Client A',
            'file_number' => 'FILE-A',
        ]);

        // Verify company_id was set automatically
        $this->assertEquals($companyA->id, $clientA->company_id);

        // 4. Verify User A can see the client
        $this->assertDatabaseHas('clients', ['id' => $clientA->id]);
        $this->assertTrue(Client::count() === 1);

        // 5. Login as User B
        \Illuminate\Support\Facades\Auth::logout();
        $this->actingAs($userB);

        // 6. Verify User B CANNOT see the client
        $this->assertTrue(Client::count() === 0);

        // 7. User B creates a client
        $clientB = Client::create([
            'first_name' => 'Client B',
            'file_number' => 'FILE-B',
        ]);
        $this->assertEquals($companyB->id, $clientB->company_id);

        // 8. Verify User B sees 1 client (their own)
        $this->assertTrue(Client::count() === 1);
        $this->assertEquals('Client B', Client::first()->first_name);

        // 9. Login as User A again
        \Illuminate\Support\Facades\Auth::logout();
        $this->actingAs($userA);

        // 10. Verify User A still only sees Client A
        $this->assertTrue(Client::count() === 1);
        $this->assertEquals('Client A', Client::first()->first_name);
    }
}