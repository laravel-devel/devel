<v-form action="{{ !empty($item) ? route('dashboard.develusers.users.update', $item) : route('dashboard.develusers.users.store') }}"
    method="POST"
    type="table"
    success="{{ route('dashboard.develusers.users.index') }}"
    :values="{{ $item ?? '{}' }}"
>
    <template v-slot:default="slotProps">
        <v-form-tab name="User"
            :fields="{{ json_encode($form) }}"
            :errors="slotProps.errors"
            :values="slotProps.values"
            :collections="{{ isset($collections) ? json_encode($collections) : '{}' }}"
            type="table"></v-form-tab>

        <v-form-tab name="Personal Permissions"
            :errors="slotProps.errors"
            :values="slotProps.values"
        >
            <p class="mb-1">
                The permissions that are granted via a role cannot be removed here.
            </p>
            <div class="flex flex-wrap">
                @foreach ($permissions as $group)
                    <div class="flex flex-column px-1">
                        <p class="text-bold mb-1">{{ $group['name'] }}</p>

                        @foreach ($group['permissions'] as $permission)
                            <v-form-el :field="{
                                type: 'checkbox',
                                name: 'permissions[]',
                                label: '{{ $permission['name'] }}',
                                value: '{{ $permission['key'] }}',
                                checked: '{{ $permission['granted'] ?? ($permission['grantedByRole'] ?? false) }}',
                                disabled: '{{ $permission['grantedByRole'] ?? false }}',
                            }" :inline="true"></v-form-el>
                        @endforeach
                    </div>
                @endforeach

                <input type="hidden" name="permissions[]">
            </div>
        </v-form-tab>
    </template>
</v-form>