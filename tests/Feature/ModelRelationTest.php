<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\RabPriority;
use App\Enums\RabStatus;
use App\Enums\UserRole;
use App\Models\Rab;
use App\Models\RabAttachment;
use App\Models\RabItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_and_rab_relations(): void
    {
        $this->seed();

        $admin = User::where('email', 'arif@sirab.local')->first();
        $this->assertNotNull($admin);
        $this->assertEquals(UserRole::ADMIN, $admin->role);
        $this->assertTrue($admin->isAdmin());

        $rab1 = Rab::where('code', 'RAB-2026-001')->with(['user', 'approver', 'items', 'attachments'])->first();
        $this->assertNotNull($rab1);
        $this->assertEquals(RabStatus::DISETUJUI, $rab1->status);
        $this->assertEquals(RabPriority::TINGGI, $rab1->priority);

        // Relasi Rab -> User
        $this->assertNotNull($rab1->user);
        $this->assertEquals('Sari Dewi', $rab1->user->name);

        // Relasi Rab -> Approver
        $this->assertNotNull($rab1->approver);
        $this->assertEquals('Drs. Arif Rachman', $rab1->approver->name);

        // Relasi Rab -> Items
        $this->assertCount(5, $rab1->items);
        $firstItem = $rab1->items->first();
        $this->assertInstanceOf(RabItem::class, $firstItem);
        $this->assertEquals($rab1->id, $firstItem->rab->id);

        // Relasi Rab -> Attachments
        $this->assertCount(2, $rab1->attachments);
        $firstAttachment = $rab1->attachments->first();
        $this->assertInstanceOf(RabAttachment::class, $firstAttachment);
        $this->assertEquals($rab1->id, $firstAttachment->rab->id);
    }
}
