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

        <v-paginator :page="page"
            :info="tableData"
            @pageChanged="onPageChanged"></v-paginator>
    </div>
</template>

<script>
import Paginator from './Paginator';

export default {
    components: {
        'v-paginator': Paginator,
    },

    props: {
        baseUrl: String,

        fields: {
            type: Object,
            default: {},
        }
    },

    computed: {
        endpoint() {
            return `${this.baseUrl}?page=${this.page}`;
        }
    },

    data() {
        return {
            fetching: false,
            tableData: [],
            items: [],
            page: 1,
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
        },

        onPageChanged(page) {
            if (page == this.page) {
                return;
            }

            this.page = page;

            this.fetchData();
        }
    }
}
</script>
