<?php

namespace Tests\Feature;

use App\Models\Feedback;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class FeedbackSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_submit_feedback_only_on_friday(): void
    {
        Notification::fake();

        $student = User::factory()->create(['role' => 'student']);

        $this->travelTo(now()->next('Friday')->setTime(10, 0));

        $this->actingAs($student)
            ->post(route('feedback.store'), [
                'title' => 'Weekly classroom feedback',
                'message' => 'Everything is working well.',
            ])
            ->assertRedirect(route('feedback.index'));

        $this->assertDatabaseHas('feedbacks', [
            'student_id' => $student->id,
            'title' => 'Weekly classroom feedback',
        ]);
    }

    public function test_student_cannot_submit_feedback_on_non_friday(): void
    {
        Notification::fake();

        $student = User::factory()->create(['role' => 'student']);

        $this->travelTo(now()->next('Monday')->setTime(10, 0));

        $this->actingAs($student)
            ->from(route('feedback.create'))
            ->post(route('feedback.store'), [
                'title' => 'Off day submission',
                'message' => 'This should be blocked.',
            ])
            ->assertSessionHasErrors('message');

        $this->assertDatabaseCount('feedbacks', 0);
    }

    public function test_student_can_submit_only_once_per_week(): void
    {
        Notification::fake();

        $student = User::factory()->create(['role' => 'student']);

        $friday = now()->next('Friday')->setTime(10, 0);
        $this->travelTo($friday);

        $this->actingAs($student)->post(route('feedback.store'), [
            'title' => 'First weekly feedback',
            'message' => 'Initial submission.',
        ])->assertRedirect(route('feedback.index'));

        $this->actingAs($student)
            ->from(route('feedback.create'))
            ->post(route('feedback.store'), [
                'title' => 'Second weekly feedback',
                'message' => 'This should be blocked until next week.',
            ])
            ->assertSessionHasErrors('message');

        $this->assertSame(1, Feedback::count());
    }
}