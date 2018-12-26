module.exports = function(object){
    var access_token = wx.getStorageSync("access_token");
    // console.log(object);
    // console.log(access_token);
    if (!object.data)
        object.data = {};
    if (access_token) {
        object.data.access_token = access_token;
    }
    object.data._uniacid = this.siteInfo.uniacid;
    object.data._acid = this.siteInfo.acid;
    wx.request({
        url: object.url,
        header: object.header || {
            'content-type': 'application/x-www-form-urlencoded'
        },
        data: object.data || {},
        method: object.method || "GET",
        dataType: object.dataType || "json",
        success: function (res) {
            if (res.data.code == -1) {
                console.log('==>-1')
                // getApp().login_1();
                wx.hideLoading();
                wx.showModal({
                    title: '提示',
                    content: '请先前往用户中心登录',
                    confirmText: '前往登录',
                    success: function (res) {
                        if (res.confirm) {
                            wx.navigateTo({
                                url: '/pages/user/user',
                            })
                        }
                    }
                })
            } else {
                if (object.success)
                    object.success(res.data);
            }
        },
        fail: function (res) {
            var app = getApp();
            if (app.is_on_launch) {
                app.is_on_launch = false;
                wx.showModal({
                    title: "网络请求出错",
                    content: res.errMsg,
                    showCancel: false,
                    success: function (res) {
                        if (res.confirm) {
                            if (object.fail)
                                object.fail(res);
                        }
                    }
                });
            } else {
                wx.showToast({
                    title: res.errMsg,
                    image: "/images/icon-warning.png",
                });
                if (object.fail)
                    object.fail(res);
            }
        },
        complete: function (res) {
            if (object.complete)
                object.complete(res);
        }
    });
};