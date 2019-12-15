define([
    'ko',
    'uiComponent',
    'jquery'
], function (ko, Component, $) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Demo_Search/ko-search',
        },

        product: ko.observableArray([]),
        check: ko.observable(0),
        searchValue: ko.observable(''),
        loading: ko.observable(0),
        initialize: function () {
            this._super();
            var self =this;
            self.thumbail = ko.observable(this.imageurl);
            return self;
        },

        checkSearch: function () {
            var self = this;
            if(self.searchValue().length >= 3 && self.check() === 0){
                self.check(0);
                self.loading(1);
                $.ajax({
                    url: this.url,
                    method: 'post',
                    showLoader: false,
                    data: {data: self.searchValue()}
                }).done(function (response) {
                    self.loading(0);
                    self.product(response);
                    $('#search-result').show();
                });
            }
            if(self.searchValue().length < 3){
                self.product([]);
                $('#search-result').hide();
            }
        },
    });
});