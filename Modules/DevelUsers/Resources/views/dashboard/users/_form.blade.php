<v-form action="{{ !empty($item) ? route('dashboard.develusers.users.update', $item) : route('dashboard.develusers.users.store') }}"
    method="POST"
    type="table"
    :fields="{{ json_encode($form) }}"
    success="{{ route('dashboard.develusers.users.index') }}"
    :values="{{ $item ?? '{}' }}"></v-form>