<template>
    <div>
        <div>
            Search:

            <v-form-el :inline="true"
                :field="{
                    type: 'text'
                }"
                v-model="searchQuery"></v-form-el>
        </div>

        <div class="table-wrapper">
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

                    <th v-if="hasActions">Actions</th>
                </thead>
                
                <tbody>
                    <tr v-for="(item, index) in items" :key="index">
                        <td v-for="key in Object.keys(fields)" :key="key">
                            {{ formatted(fields[key], item[key]) }}
                        </td>

                        <td v-if="hasActions">
                            <a v-if="actions.delete"
                                href="#"
                                class="action-btn danger"
                                title="Delete"
                                @click.prevent="deleteItem(item, actions.delete)"
                            >
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-show="processing" class="processing-overlay">
                <i class="las la-sync spin-ease"></i>
            </div>
        </div>

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
        },

        actions: {
            default: {},
        }
    },

    computed: {
        endpoint() {
            return `${this.baseUrl}?page=${this.page}&sort=${this.sort}|${this.sortAsc ? 'asc' : 'desc'}&search=${this.searchQuery}`;
        },

        hasActions() {
            return this.actions && Object.keys(this.actions).length > 0;
        }
    },

    data() {
        return {
            processing: false,
            tableData: [],
            items: [],
            page: 1,
            sort: Object.keys(this.fields)[0],
            sortAsc: true,
            searchQuery: '',
            searchTimeout: null,
        };
    },
 
    created() {
        this.fetchData();
    },

    watch: {
        searchQuery() {
            clearTimeout(this.searchTimeout);

            this.searchTimeout = setTimeout(() => {
                this.fetchData();
            }, 250);
        }
    },

    methods: {
        fetchData() {
            this.processing = true;

            axios.get(this.endpoint)
                .then(({ data }) => {
                    this.tableData = data;
                    this.items = data.data;

                    this.processing = false;
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
        },

        formatted(field, value) {
            let formatted = value;

            if (field.format) {
                formatted = eval(field.format);
            }
            
            return formatted ? formatted : '-';
        },

        deleteItem(item, url) {
            this.processing = true;

            const params = url.match(new RegExp(':([a-zA-Z].*?)(/|$)', 'g'));

            for (let param of params) {
                param = param.replace('/', '');
                const attr = param.replace(':', '');

                if (item[attr]) {
                    url = url.replace(param, item[attr]);
                }
            }
            
            // TODO: Confirmation modal

            axios.delete(url)
                .then((response) => {
                    // TODO: A success notification
                    this.fetchData();
                })
                .catch(({ response }) => {
                    // TODO: An error notification
                    if (response.status == 409) {
                        alert(response.data.message);
                    } else {
                        alert('Something went wrong!');
                    }

                    this.processing = false;
                });
        }
    }
}
</script>
