<v-form action="{{ !empty($item) ? route('dashboard.develusers.users.update', $item) : route('dashboard.develusers.users.store') }}"
    method="POST"
    type="table"
    :fields="{{ json_encode($form) }}"
    success="{{ route('dashboard.develusers.users.index') }}"
    :values="{{ $item ?? '{}' }}"
    :collections="{{ isset($collections) ? json_encode($collections) : '{}' }}"></v-form>
{{-- // TODO: Add "collections" to the CRUD generation. I should be able to generate this stuff --}}