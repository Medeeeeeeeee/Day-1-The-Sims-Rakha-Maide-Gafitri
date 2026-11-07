<?php

namespace Tests\Feature;

use App\Models\Lab;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_ticket()
    {
        $lab = Lab::factory()->create(['pc_count' => 5]);

        $response = $this->post('/tickets', [
            'lab_id' => $lab->id,
            'nomor_pc' => 1,
            'nama_pelapor' => 'Anon',
            'judul' => 'Oops',
            'deskripsi' => 'Something broke',
            'ruangan' => 'R.01',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('tickets', ['judul' => 'Oops']);
    }

    public function test_authenticated_can_update_ticket_status()
    {
        $user = User::factory()->create(['role' => 'asisten']);
        $lab = Lab::factory()->create(['pc_count' => 3]);
        $ticket = Ticket::factory()->create([
            'lab_id' => $lab->id,
            'nomor_pc' => 1,
        ]);

        $response = $this->actingAs($user)->put('/tickets/'.$ticket->id, [
            'lab_id' => $lab->id,
            'nomor_pc' => $ticket->nomor_pc,
            'judul' => $ticket->judul,
            'deskripsi' => $ticket->deskripsi,
            'status' => 'proses',
        ]);

        $response->assertRedirect('/tickets');
        $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'proses']);
    }

    public function test_guest_cannot_access_ticket_edit()
    {
        $ticket = Ticket::factory()->create();
        $response = $this->get('/tickets/'.$ticket->id.'/edit');
        $response->assertStatus(403);
    }

    public function test_guest_can_view_mahasiswa_dashboard_counts()
    {
        // create some tickets with statuses
        Ticket::factory()->count(2)->create(['status' => 'pending']);
        Ticket::factory()->count(3)->create(['status' => 'proses']);
        Ticket::factory()->count(1)->create(['status' => 'selesai']);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Ticket Dibuat');
        $response->assertSee('Diproses');
        $response->assertSee('Selesai');
        $response->assertSee('6'); // total
    }

    public function test_admin_dashboard_requires_login()
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_admin_user_sees_admin_dashboard()
    {
        Ticket::factory()->count(4)->create();
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Admin');
        $response->assertSee('Selamat Datang');
        $response->assertSee('Kelola Lab');
    }

    public function test_navbar_do_not_show_labs_link_anymore()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/tickets');
        $response->assertDontSee('Labs');
    }

    public function test_non_admin_cannot_view_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_login_redirects_to_admin_dashboard()
    {
        $user = User::factory()->create([ 'role' => 'admin', 'email' => 'admin@example.com' ]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        // Laravel's default factory uses 'password' plaintext
        $response->assertRedirect('/admin');
    }

    public function test_regular_user_login_goes_to_student_dashboard()
    {
        $user = User::factory()->create([ 'role' => 'user', 'email' => 'user@example.com' ]);
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $response->assertRedirect('/dashboard');
    }

    // lab management page tests
    public function test_lab_index_shows_list_and_add_button_to_admin()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $lab = Lab::factory()->create(['pc_count' => 5]);

        $response = $this->actingAs($user)->get('/labs');
        $response->assertStatus(200);
        $response->assertSee('Kelola Lab');
        $response->assertSee('Tambah Lab');
        $response->assertSee($lab->nama_lab);
        $response->assertSee('Edit');
        $response->assertSee('Hapus');
        $response->assertSee('Lihat');
    }

    public function test_admin_sees_create_form_on_create_page()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->get('/labs/create');
        $response->assertStatus(200);
        $response->assertSee('Tambah Lab');
        $response->assertSee('Nomor Lab');
        $response->assertSee('Jumlah PC');
    }

    public function test_admin_can_create_lab_from_create_page()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->post('/labs', [
            'nama_lab' => '202',
            'pc_count' => 8,
        ], ['HTTP_REFERER' => '/labs/create']);
        $response->assertRedirect('/labs');
        $this->assertDatabaseHas('labs', ['nama_lab' => '202']);
    }

    public function test_admin_can_view_lab_details()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $lab = Lab::factory()->create(['pc_count' => 4]);
        $response = $this->actingAs($user)->get("/labs/{$lab->id}");
        $response->assertStatus(200);
        $response->assertSee($lab->nama_lab);
        $response->assertSee((string) $lab->pc_count);
    }
}
