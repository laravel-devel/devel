Vue.prototype.$alert = function (message = '', options = {}) {
    if (typeof message !== 'string') {
        return;
    }

    Events.$emit('alert', { message: message, options: options });
}

Vue.prototype.$confirm = function (message = '', options = {}) {
    if (typeof message !== 'string') {
        return;
    }

    Events.$emit('confirm', { message: message, options: options });
}

Vue.prototype.$notify = function (message = '', type = 'info') {
    if (typeof message !== 'string' || !message) {
        return;
    }

    Events.$emit('notify', { message: message, type: type });
}