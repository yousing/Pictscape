var PopupWindow = Class.create();
PopupWindow.prototype = {
    initialize: function(className, parentElement) {
        var elements = document.getElementsByClassName(className, parentElement);
        for (var i = 0, len = elements.length; i < len; i++) {
            Event.observe(elements[i], 'click', this.addPopupEvent.bindAsEventListener(this));
            Event.observe(elements[i], 'keypress', this.addPopupEvent.bindAsEventListener(this));
        }
    },
    addPopupEvent: function(event) {
        var element = Event.element(event);
        var link = element.getAttribute('href');
        window.open(link);
        Event.stop(event);
    }
};
Event.observe(window, 'load', function () {
    new PopupWindow('popup', 'p');
});