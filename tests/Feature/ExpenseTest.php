<?php

namespace Tests\Feature;
use App\Models\Expense;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExpenseTest extends TestCase
{

    use RefreshDatabase; 
    /**
     * A basic feature test example.
     */

     public function an_expense_can_be_created()
     {
         $response = $this->post('/expenses', [
             'description' => 'Test Expense',
             'amount'      => 100.00,
             'category'    => 'Food',
             'date'        => now()->toDateString(),
         ]);
 
         $response->assertStatus(302); // Check if it redirects after creation
 
         // Assert that the expense is in the database
         $this->assertDatabaseHas('expenses', [
             'description' => 'Test Expense',
             'amount'      => 100.00,
             'category'    => 'Food',
         ]);
     }
 
     /** @test */
    
     public function an_expense_can_be_shown()
     {
         // Create a user first
         $user = User::factory()->create();
         
         // Now create an expense associated with that user
         $expense = Expense::create([
             'user_id' => $user->id,  // Add the user_id to associate the expense with the user
             'description' => 'Sample expense',
             'amount' => 100.00,
             'category' => 'Food',
             'date' => now(),
         ]);
         
         $response = $this->get("/expenses/{$expense->id}");
     
         $response->assertStatus(200);
     }
     
 
     /** @test */
     public function an_expense_can_be_updated()
     {
         // Create an expense to update
         $expense = Expense::factory()->create();
 
         $response = $this->put("/expenses/{$expense->id}", [
             'description' => 'Updated Expense',
             'amount'      => 150.00,
             'category'    => 'Transport',
             'date'        => now()->toDateString(),
         ]);
 
         $response->assertStatus(302); // Check if it redirects after update
 
         // Assert that the expense was updated in the database
         $this->assertDatabaseHas('expenses', [
             'description' => 'Updated Expense',
             'amount'      => 150.00,
             'category'    => 'Transport',
         ]);
     }
 
     /** @test */
     public function an_expense_can_be_deleted()
     {
         // Create an expense to delete
         $expense = Expense::factory()->create();
 
         $response = $this->delete("/expenses/{$expense->id}");
         $response->assertStatus(302); // Check if it redirects after delete
 
         // Assert that the expense was deleted from the database
         $this->assertDatabaseMissing('expenses', [
             'id' => $expense->id,
         ]);
     }
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
