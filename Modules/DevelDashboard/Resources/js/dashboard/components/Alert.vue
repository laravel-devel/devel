<template>
    <div v-if="this.alerts.length > 0" class="full-overlay">
        <div class="card alert">
            <div class="message">
                {{ lastAlert.message }}
            </div>

            <hr>

            <div class="buttons">
                <a href="#"
                    class="btn link"
                    @click.prevent="onOk">OK</a>

                <a v-if="lastAlert.type === 'confirm'"
                    href="#"
                    class="btn link"
                    @click.prevent="onCancel">Cancel</a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            alerts: [],
        };
    },

    computed: {
        lastAlert() {
            return this.alerts[this.alerts.length - 1];
        }
    },
 
    created() {
        Events.$on('alert', (options) => {
            this.alerts.push(Object.assign(options, { type: 'alert' }));
        });

        Events.$on('confirm', (options) => {
            this.alerts.push(Object.assign(options, { type: 'confirm' }));
        });
    },

    methods: {
        onOk() {
            if (this.lastAlert.options.onOk) {
                this.lastAlert.options.onOk();
            }

            this.alerts.pop();
        },

        onCancel() {
            if (this.lastAlert.options.onCancel) {
                this.lastAlert.options.onCancel();
            }

            this.alerts.pop();
        },
    }
}
</script>
