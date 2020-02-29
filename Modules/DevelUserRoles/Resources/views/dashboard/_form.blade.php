<v-form action="{{ !empty($item) ? route('dashboard.develuserroles.update', $item) : route('dashboard.develuserroles.store') }}"
    method="POST"
    type="table"
    success="{{ route('dashboard.develuserroles.index') }}"
    :values="{{ $item ?? '{}' }}"
>
    <template v-slot:default="slotProps">
        <v-form-tab name="Role"
            :fields="{{ json_encode($form['Main']) }}"
            :errors="slotProps.errors"
            :values="slotProps.values"
            type="table"></v-form-tab>

        <v-form-tab name="Permissions"
            :errors="slotProps.errors"
            :values="slotProps.values"
        >
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
                                checked: '{{ $permission['granted'] ?? false }}',
                            }" :inline="true"></v-form-el>
                        @endforeach
                    </div>
                @endforeach

                <input type="hidden" name="permissions[]">
            </div>
        </v-form-tab>
    </template>
</v-form>