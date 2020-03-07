<template>
    <div :class="{ 'hidden': !show }">
        <slot>
            <div v-if="type === 'default'">
                <v-form-el v-for="(field, index) in fields"
                    :key="index"
                    :field="field"
                    :collections="collections"
                    class="pb-1">
                </v-form-el>
            </div>

            <div v-else-if="type === 'table'">
                <table>
                    <tr v-for="(field, index) in fields"
                        :key="index"
                    >
                        <td class="pb-1 pt-05" v-text="field.label"></td>

                        <td class="pb-1">
                            <v-form-el :field="field"
                                :collections="collections"
                                :show-label="false"
                                :inline="true">
                            </v-form-el>
                        </td>
                    </tr>
                </table>
            </div>
        </slot>
    </div>
</template>

<script>
export default {
    props: {
        fields: Array,
        
        values: Object,

        type: {
            type: String,
            default: 'default',
        },

        collections: {
            type: Object,
            default: () => {
                return {};
            },
        },
        
        errors: {},

        show: {
            type: Boolean,
            
            default: false,
        },

        readOnly: {
            type: Boolean,
            default: false,
        },
    },

    data() {
        return {
            tabReadOly: this.readOnly || this.$parent.readOnly,
        };
    },
}
</script>
