<template>
    <div v-if="this.notifications.length > 0" class="notifications-overlay">
        <div v-for="notification in notifications"
            :key="notification.id"
            class="card notification"
            :class="type(notification)"
            @click="hide(notification)"
        >
            {{ notification.message }}
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            notifications: [],
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

            notification.timeout = setTimeout(() => {
                this.hide(notification);
            }, 3000);

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
