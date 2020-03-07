<template>
    <div class="datatable">
        <div class="flex pb-1">
            <div class="flex flex-align-center flex-1">
                <span class="mr-1">Search:</span>

                <v-form-el :inline="true"
                    :field="{
                        type: 'text'
                    }"
                    v-model="searchQuery"
                    class="search-field"></v-form-el>
            </div>

            <div v-if="hasActions && actions.create && allowedTo('create')">
                <a :href="actions.create" class="btn">Add</a>
            </div>
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

                        <td v-if="hasActions" class="actions">
                            <a v-if="actions.delete && allowedTo('delete')"
                                href="#"
                                class="action-btn danger"
                                title="Delete"
                                @click.prevent="deleteItemConfirm(item, actions.delete)"
                            >
                                <i class="las la-trash"></i>
                            </a>

                            <a v-if="actions.edit && (allowedTo('edit') || allowedTo('view'))"
                                :href="actionEndpoint(actions.edit, item)"
                                class="action-btn primary"
                                title="Edit"
                            >
                                <i class="las la-edit"></i>
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
            default: () => {
                return {};
            },
        },

        permissions: {
            default: () => {
                return {};
            },
        },

        deleteConfirmation: {
            type: String,
            default: 'Are you sure you want to delete this item?',
        },
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

        actionEndpoint(url, item) {
            const params = url.match(new RegExp(':([a-zA-Z].*?)(/|$)', 'g'));

            for (let param of params) {
                param = param.replace('/', '');
                const attr = param.replace(':', '');

                if (item[attr]) {
                    url = url.replace(param, item[attr]);
                }
            }

            return url;
        },

        deleteItemConfirm(item, url) {
            this.$confirm(this.deleteConfirmation, {
                onOk: () => {
                    this.deleteItem(item, url);
                }
            });
        },

        deleteItem(item, url) {
            this.processing = true;

            url = this.actionEndpoint(url, item);

            axios.delete(url)
                .then((response) => {
                    this.fetchData();

                    this.$notify('Item has been deleted!', 'success');
                })
                .catch(({ response }) => {
                    let error = response.data.message
                        ? response.data.message
                        : 'Something went wrong!';

                    this.$notify(error, 'error');

                    this.processing = false;
                });
        },

        allowedTo(action) {
            // If a permissions for an action weren't specified - it is allowed
            if (!this.permissions[action]) {
                return true;
            }

            return $auth.hasPermissions(this.permissions[action]);
        },
    }
}
</script>
