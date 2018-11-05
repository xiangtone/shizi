var _api_root = '{$_api_root}';
var api = {
    default: {
        index: _api_root + 'default/index',
        store: _api_root + 'default/store',
        cat: _api_root + 'default/cat',
        cat_video: _api_root + 'default/cat-video',
        search: _api_root + 'default/search',
        hot_search: _api_root + 'default/hot-search',
        upload_img: _api_root + 'default/upload-img',
        share: _api_root + 'default/share'
    },
    passport: {
        login: _api_root + 'passport/login',
    },
    user: {
        collect: _api_root + 'user/collect',
        video: _api_root + 'user/video',
        collect_video: _api_root + 'user/collect-video',
        video_list: _api_root + 'user/video-list',
        comment: _api_root + 'user/comment',
        comment_list: _api_root + 'user/comment-list',
        thump: _api_root + 'user/thump',
        user_comment: _api_root + 'user/user-comment',
        index: _api_root + 'user/index',
        buy_video: _api_root + 'user/buy-video',
        coupon_receive: _api_root + 'user/coupon-receive',
        user_coupon: _api_root + 'user/user-coupon',
        user_coupon_qrcode: _api_root + 'user/user-coupon-qrcode',
        cancel_coupon: _api_root + 'user/cancel-coupon',
        video_coupon: _api_root + 'order/video-coupon',
        clerk: _api_root + 'user/clerk',
        user_binding: _api_root + 'user/user-binding',
        user_hand_binding: _api_root + 'user/user-hand-binding',
        user_empower: _api_root + 'user/user-empower',
        sms_setting: _api_root + 'user/sms-setting',
    },
    order: {
        list: _api_root + 'order/list',
        prew: _api_root + 'order/prew',
        get_pay_data: _api_root + 'order/get-pay-data',
        qrcode: _api_root + 'order/qrcode',
        detail: _api_root + 'order/detail',
        clerk_detail: _api_root + 'order/clerk-detail',
        clerk: _api_root + 'order/clerk',
        refund: _api_root + 'order/refund',
        refund_prew: _api_root + 'order/refund-prew',
        refund_detail: _api_root + 'order/refund-detail',
        video: _api_root + 'order/video',
        member_order: _api_root + 'order/member-order',
        get_member_data: _api_root + 'order/get-member-data',
        get_member_data: _api_root + 'order/get-member-data',
        video_coupon: _api_root + 'order/video-coupon',
    },
    member: {
        index: _api_root + 'member/index',
    }
};
module.exports = api;