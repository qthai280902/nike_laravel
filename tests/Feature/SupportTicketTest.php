<?php

namespace Tests\Feature;

use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SupportTicketTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function guest_can_submit_support_ticket_successfully(): void
    {
        $ticketData = [
            'name' => 'Nguyen Van A',
            'email' => 'vana@example.com',
            'subject' => 'Lỗi kết nối thanh toán',
            'message' => 'Tôi đã thanh toán nhưng đơn hàng chưa được xác nhận.',
        ];

        $response = $this->post(route('support.store'), $ticketData);

        $response->assertRedirect(route('support.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => null,
            'name' => 'Nguyen Van A',
            'email' => 'vana@example.com',
            'subject' => 'Lỗi kết nối thanh toán',
            'message' => 'Tôi đã thanh toán nhưng đơn hàng chưa được xác nhận.',
            'status' => 'open',
            'admin_note' => null,
        ]);
    }

    #[Test]
    public function logged_in_user_can_submit_support_ticket_successfully(): void
    {
        $user = User::factory()->create([
            'name' => 'User Dang Nhap',
            'email' => 'user@example.com',
            'role' => 'customer',
        ]);

        $ticketData = [
            'name' => 'User Dang Nhap Override', // prefilled but can be customized
            'email' => 'user@example.com',
            'subject' => 'Tư vấn chọn size giày Air Max',
            'message' => 'Chân mình dài 26cm thì nên chọn size 41 hay 42 vậy shop?',
        ];

        $response = $this->actingAs($user)->post(route('support.store'), $ticketData);

        $response->assertRedirect(route('support.create'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('support_tickets', [
            'user_id' => $user->id,
            'name' => 'User Dang Nhap Override',
            'email' => 'user@example.com',
            'subject' => 'Tư vấn chọn size giày Air Max',
            'status' => 'open',
        ]);
    }

    #[Test]
    public function admin_can_view_support_tickets_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = SupportTicket::create([
            'name' => 'Khach Lam Support',
            'email' => 'khach@example.com',
            'subject' => 'Can ho tro gap',
            'message' => 'He thong cham qua',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.support.index'));

        $response->assertStatus(200);
        $response->assertSee('Can ho tro gap');
        $response->assertSee('Khach Lam Support');
    }

    #[Test]
    public function admin_can_view_support_ticket_details(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = SupportTicket::create([
            'name' => 'Khach Lam Support',
            'email' => 'khach@example.com',
            'subject' => 'Can ho tro gap',
            'message' => 'He thong cham qua',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.support.show', $ticket));

        $response->assertStatus(200);
        $response->assertSee('Can ho tro gap');
        $response->assertSee('He thong cham qua');
    }

    #[Test]
    public function admin_can_update_support_ticket_status_and_notes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = SupportTicket::create([
            'name' => 'Khach Lam Support',
            'email' => 'khach@example.com',
            'subject' => 'Can ho tro gap',
            'message' => 'He thong cham qua',
            'status' => 'open',
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.support.update', $ticket), [
            'status' => 'in_progress',
            'admin_note' => 'Đã liên hệ khách hàng qua điện thoại để hỗ trợ.',
        ]);

        $response->assertRedirect(route('admin.support.show', $ticket));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('support_tickets', [
            'id' => $ticket->id,
            'status' => 'in_progress',
            'admin_note' => 'Đã liên hệ khách hàng qua điện thoại để hỗ trợ.',
        ]);
    }

    #[Test]
    public function customer_cannot_access_admin_support_routes(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $ticket = SupportTicket::create([
            'name' => 'Khach Lam Support',
            'email' => 'khach@example.com',
            'subject' => 'Can ho tro gap',
            'message' => 'He thong cham qua',
            'status' => 'open',
        ]);

        $response1 = $this->actingAs($customer)->get(route('admin.support.index'));
        $response1->assertStatus(404);

        $response2 = $this->actingAs($customer)->get(route('admin.support.show', $ticket));
        $response2->assertStatus(404);

        $response3 = $this->actingAs($customer)->patch(route('admin.support.update', $ticket), [
            'status' => 'resolved',
            'admin_note' => 'Unauthorized try',
        ]);
        $response3->assertStatus(404);
    }

    #[Test]
    public function guest_cannot_access_admin_support_routes(): void
    {
        $ticket = SupportTicket::create([
            'name' => 'Khach Lam Support',
            'email' => 'khach@example.com',
            'subject' => 'Can ho tro gap',
            'message' => 'He thong cham qua',
            'status' => 'open',
        ]);

        $response1 = $this->get(route('admin.support.index'));
        $response1->assertRedirect(route('login'));

        $response2 = $this->get(route('admin.support.show', $ticket));
        $response2->assertRedirect(route('login'));

        $response3 = $this->patch(route('admin.support.update', $ticket), [
            'status' => 'resolved',
        ]);
        $response3->assertRedirect(route('login'));
    }
}
