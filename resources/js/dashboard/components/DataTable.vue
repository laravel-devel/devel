<template>
    <div>
        <table class="table card">
            <thead>
                <th v-for="key in Object.keys(fields)" :key="key">
                    {{ fields[key] }}
                </th>
            </thead>
            
            <tbody>
                <tr v-for="(item, index) in items" :key="index">
                    <td v-for="key in Object.keys(fields)" :key="key">
                        {{ item[key] ? item[key] : '-' }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        baseUrl: String,

        fields: {
            type: Object,
            default: {},
        }
    },

    computed: {
        endpoint() {
            return this.baseUrl;
        }
    },

    data() {
        return {
            fetching: false,
            tableData: [],
            items: [],
        };
    },
 
    created() {
        this.fetchData();
    },

    methods: {
        fetchData() {
            this.fetching = true;

            axios.get(this.endpoint)
                .then(({ data }) => {
                    this.tableData = data;
                    this.items = data.data;

                    this.fetching = false;
                });
        }
    }
}
</script>
