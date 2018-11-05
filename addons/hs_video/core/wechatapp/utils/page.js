module.exports = {
    currentPage: null,
    onLoad: function (page) {
        console.log('--------pageOnLoad----------');
        this.currentPage = page;
        var _this = this;
        getApp().getCallBack = function (res) {
            if (typeof res == 'object') {
                page.setData(res);
            }
        }
    },
    onReady: function (page) {
        console.log('--------pageOnReady----------');
        this.currentPage = page;
    },
    onShow: function (page) {
        console.log('--------pageOnShow----------');
        this.currentPage = page;
    },
    onHide: function (page) {
        console.log('--------pageOnHide----------');
        this.currentPage = page;
    },
    onUnload: function (page) {
        console.log('--------pageOnUnload----------');
        this.currentPage = page;
    },

    showToast: function (e) {
        console.log('--- showToast ---');
        var page = this.currentPage;
        var duration = e.duration || 2500;
        var title = e.title || '';
        var success = e.success || null;
        var fail = e.fail || null;
        var complete = e.complete || null;
        if (page._toast_timer) {
            clearTimeout(page._toast_timer);
        }
        page.setData({
            _toast: {
                title: title,
            },
        });
        page._toast_timer = setTimeout(function () {
            var _toast = page.data._toast;
            _toast.hide = true;
            page.setData({
                _toast: _toast,
            });
            if (typeof complete == 'function') {
                complete();
            }
        }, duration);
    },
    formIdFormSubmit: function (e) {
        console.log('--- formIdFormSubmit ---', e);
    },
    setDeviceInfo: function () {
        var page = this.currentPage;
        //iphonex=>iPhone X(GSM+CDMA)<iPhone10,3>
        var device_list = [
            {
                id: 'device_iphone_5',
                model: 'iPhone 5',
            },
            {
                id: 'device_iphone_x',
                model: 'iPhone X',
            },
        ];
        //设置设备信息
        var device_info = wx.getSystemInfoSync();
        if (device_info.model) {
            if (device_info.model.indexOf('iPhone X') >= 0) {
                device_info.model = 'iPhone X';
            }
            for (var i in device_list) {
                if (device_list[i].model == device_info.model) {
                    page.setData({
                        __device: device_list[i].id,
                    });
                }
            }
        }
    },
    setPageNavbar: function (page) {
        console.log('----setPageNavbar----');
        console.log(page);
        var navbar = wx.getStorageSync('_navbar');

        if (navbar) {
            setNavbar(navbar);
        }
        var in_array = false;
        for (var i in this.navbarPages) {
            if (page.route == this.navbarPages[i]) {
                in_array = true;
                break;
            }
        }
        if (!in_array) {
            console.log('----setPageNavbar Return----');
            return;
        }

        getApp().request({
            url: getApp().api.default.navbar,
            success: function (res) {
                if (res.code == 0) {
                    setNavbar(res.data);
                    wx.setStorageSync('_navbar', res.data);
                }
            }
        });

        function setNavbar(navbar) {
            var in_navs = false;
            var route = page.route || (page.__route__ || null);
            for (var i in navbar.navs) {
                if (navbar.navs[i].url === "/" + route) {
                    navbar.navs[i].active = true;
                    in_navs = true;
                } else {
                    navbar.navs[i].active = false;
                }
            }
            if (!in_navs)
                return;
            page.setData({ _navbar: navbar });
        }

    },
    //加入底部导航的页面
    navbarPages: [
    ],
};