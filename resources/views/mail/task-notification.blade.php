<!-- resources/views/emails/task-notification.blade.php -->

<div style="background-color: #f2f2f2; padding: 20px;">
    <h2 style="color: #333;">Task Notification</h2>
    <hr>
    @if ($notificationType === 'completion')
        <p style="color: #4CAF50;">Your task "{{ $task->name }}" has been completed successfully.</p>
    @else
        <p style="color: #2196F3;">A new task "{{ $task->name }}" has been created.</p>
    @endif
    <p style="color: #888;">Thank you for using our task management system.</p>
</div>
