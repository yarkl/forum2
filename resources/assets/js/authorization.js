let user = window.App.user;

module.exports = {
    owns (model) {
        return model['user_id'] === user.id;
    }
};