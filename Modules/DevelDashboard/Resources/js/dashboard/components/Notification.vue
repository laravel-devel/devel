<template>
    <div v-if="this.notifications.length > 0" class="notifications-overlay">
        <div v-for="notification in notifications"
            :key="notification.id"
            class="card notification"
            :class="type(notification)"
            v-text="notification.message"
            @click="hide(notification)"
        ></div>
    </div>
</template>

<script>
export default {
    props: {
        flash: {
            type: Array,
            default: () => {
                return [];
            },
        }
    },

    data() {
        return {
            notifications: this.flash,
            id: 0,
            types: [
                'info',
                'success',
                'error',
            ],
        };
    },

    created() {
        Events.$on('notify', (options) => {
            const notification = Object.assign(options, { id: this.id++ });
            const duration = notification.message.length * 30;

            notification.timeout = setTimeout(() => {
                this.hide(notification);
            }, (duration > 3000 ? duration : 3000));

            this.notifications.push(notification);
        });

    },

    methods: {
        type(options) {
            if (!options.type) {
                return 'info';
            }

            if (this.types.indexOf(options.type) < 0) {
                return 'info';
            }

            return options.type;
        },
        
        hide(notification) {
            const index = this.notifications.indexOf(notification);

            if (index === undefined) {
                return;
            }

            clearTimeout(notification.timeout);
            
            this.notifications.splice(index, 1);
        }
    }
}
</script>
