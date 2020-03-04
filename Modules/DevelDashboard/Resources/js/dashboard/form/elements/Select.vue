<template>
    <div>
        <label v-if="attrs.label" v-text="attrs.label"></label>

        <div class="select">
            <div class="select-input">
                <input type="text"
                    class="form-element"
                    placeholder="Start typing..."
                    autocomplete="off"
                    v-model="search"
                    ref="input"
                    :disabled="attrs.disabled"
                    @focus="open = true">

                <div class="select-arrow"
                    :class="{ 'open': open, 'disabled': attrs.disabled }"
                    @click="toggleOpen"
                    ref="arrow"
                >
                    <i v-if="open" class="las la-angle-up" ref="arrow-up"></i>
                    <i v-else class="las la-angle-down" ref="arrow-down"></i>
                </div>
            </div>

            <div v-show="open" class="select-dropdown">
                <ul>
                    <li v-for="(option, index) in filteredOptions"
                        :key="index"
                        @click="selectOption(option)">{{ option[textField] }}</li>
                </ul>
            </div>

            <div class="select-selected-items">
                <div v-for="(option, index) in selectedOptions"
                    :key="index"
                    class="select-seleced-item"
                >
                    <div class="text">{{ option[textField] }}</div>

                    <div v-if="!attrs.disabled"
                        class="remove"
                        @click="unselectOption(option)"
                    >
                        <i class="las la-times"></i>
                    </div>
                </div>
            </div>
        </div>

        <template v-if="multipleChoice && selections.length > 0">
            <input v-for="(selection, index) in selections"
                :key="index"
                type="hidden"
                :name="`${attrs.name}[]`"
                autocomplete="off"
                :value="selection">
        </template>

        <input v-else
            type="hidden"
            :name="attrs.name"
            autocomplete="off"
            v-model="selections">
    </div>
</template>

<script>
export default {
    props: ['attrs', 'value', 'collections'],

    data() {
        return {
            options: (this.collections && this.collections[this.attrs.name])
                ? this.collections[this.attrs.name]
                : [],
            selectedOptions: [],
            availableOptions: [],
            filteredOptions: [],
            idField: '',
            textField: '',
            multipleChoice: false,
            open: false,
            search: '',
            selections: [],
        };
    },

    created() {
        if (!this.attrs.attrs || !this.attrs.attrs.idField || !this.attrs.attrs.textField) {
            throw 'Missing attributes for the select field.';
        }

        this.idField = this.attrs.attrs.idField;
        this.textField = this.attrs.attrs.textField;
        this.multipleChoice = (this.attrs.attrs.multipleChoice == true);

        if (this.value) {
            for (let item of this.value) {
                this.selectedOptions.push(this.formatOption(item));
            }
        }

        this.calculateAvailableOptions();
        this.filterOptions();
    },

    mounted() {
        document.addEventListener('click', (e) => {
            const condition = e.target === this.$refs['input']
                || e.target === this.$refs['arrow']
                || e.target === this.$refs['arrow-up']
                || e.target === this.$refs['arrow-down'];

            if (!condition) {
                this.open = false;
            }
        });
    },

    watch: {
        selectedOptions() {
            this.onSelectionsUpdated();
        },

        search(newValue) {
            this.filterOptions();
        },
    },

    methods: {
        formatOption(option) {
            return {
                [this.idField]: option[this.idField],
                [this.textField]: option[this.textField],
            };
        },

        calculateAvailableOptions() {
            this.availableOptions.splice(0);
            
            for (let option of this.options) {
                const selected = this.selectedOptions.find(item => {
                    return item[this.idField] === option[this.idField];
                });

                if (selected === undefined) {
                    this.availableOptions.push(this.formatOption(option));
                }
            }
        },

        filterOptions() {
            this.filteredOptions.splice(0);

            const search = this.search.trim().toLowerCase();

            this.filteredOptions = this.availableOptions.filter(item => {
                return item[this.textField].toLowerCase().indexOf(search) >= 0;
            });
        },

        toggleOpen() {
            if (!this.attrs.disabled) {
                this.open = ! this.open;
            }
        },

        selectOption(option) {
            this.selectedOptions.push(this.formatOption(option));

            this.search = '';
            this.open = false;
        },

        unselectOption(option) {
            const index = this.selectedOptions.indexOf(option);

            if (index >= 0) {
                this.selectedOptions.splice(index, 1);
            }
        },

        onSelectionsUpdated() {
            this.calculateAvailableOptions();
            this.filterOptions();

            this.selections.splice(0);
            this.selections = this.selectedOptions.map(item => item[this.idField]);

            this.$emit('input', this.selections);
        }
    }
}
</script>
