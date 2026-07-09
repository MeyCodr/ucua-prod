@if ($companyId == 0)
{{ $otherCompanyName }}
@else
{{ \App\Models\Company::find($companyId)->company_name }}
@endif