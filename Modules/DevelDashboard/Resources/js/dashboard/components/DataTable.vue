<template>
    <div>
        <table class="table card">
            <thead>
                <th v-for="key in Object.keys(fields)"
                    :key="key"
                    :class="{ 'sortable': fields[key].sortable }"
                    @click="toggleSort(key)"
                >
                    {{ fields[key].name }}

                    <span v-if="fields[key].sortable">
                        <i v-if="sort === key && sortAsc" class="las la-sort-up"></i>

                        <i v-else-if="sort === key" class="las la-sort-down"></i>
                        
                        <i v-else class="las la-sort"></i>
                    </span>
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
            required: true,
        }
    },

    computed: {
        endpoint() {
            return `${this.baseUrl}?page=${this.page}&sort=${this.sort}|${this.sortAsc ? 'asc' : 'desc'}`;
        }
    },

    data() {
        return {
            fetching: false,
            tableData: [],
            items: [],
            page: 1,
            sort: Object.keys(this.fields)[0],
            sortAsc: true,
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
        },

        toggleSort(key) {
            if (!this.fields[key].sortable) {
                return;
            }

            this.sortAsc = (this.sort !== key) ? true : ! this.sortAsc;
            this.sort = key;

            this.fetchData();
        }
    }
}
</script>
