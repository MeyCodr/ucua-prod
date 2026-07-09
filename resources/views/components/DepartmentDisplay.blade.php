@if ($departmentId == 0)
{{ $otherDepartmentName }}
@else
{{ \App\Models\Department::find($departmentId)->name }}
@endif
