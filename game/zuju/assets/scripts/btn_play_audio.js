// Learn cc.Class:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/class.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/class.html
// Learn Attribute:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/reference/attributes.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/reference/attributes.html
// Learn life-cycle callbacks:
//  - [Chinese] http://docs.cocos.com/creator/manual/zh/scripting/life-cycle-callbacks.html
//  - [English] http://www.cocos2d-x.org/docs/creator/en/scripting/life-cycle-callbacks.html

cc.Class({
    extends: cc.Component,

    properties: {
        // foo: {
        //     // ATTRIBUTES:
        //     default: null,        // The default value will be used only when the component attaching
        //                           // to a node for the first time
        //     type: cc.SpriteFrame, // optional, default is typeof default
        //     serializable: true,   // optional, default is true
        // },
        // bar: {
        //     get () {
        //         return this._bar;
        //     },
        //     set (value) {
        //         this._bar = value;
        //     }
        // },
    },

    // LIFE-CYCLE CALLBACKS:

    // onLoad () {},

    start() {

    },
    on_click() {

        //方法1
        //https://www.dushujielong.com/ll/shizi/hit01.mp3
        //http://qiniu.agsew.com/uploads/video/20181213141801/1544681881f67356259ffa3871.mp3
        //cc.audioEngine.playEffect("http://qiniu.agsew.com/uploads/video/20181224191338/154565001839384e2287147c42.mp3", false);
        //wx.miniProgram.navigateBack();
        //cc.director.end();
        
        //console.log("播放声音",cc.sys.isBrowser,cc.sys.os,cc.sys.OS_WINDOWS);
        cc.loader.load("http://qiniu.agsew.com/uploads/video/20181213141801/1544681881f67356259ffa3871.mp3",  function (err, clip) {
            console.log(err,clip);
            //var audioID = cc.audioEngine.play(clip, false);
            var audioID = cc.audioEngine.playEffect(clip, false);
            if(cc.sys.os ==cc.sys.OS_WINDOWS){
                window.close();
                //window.history.back();
            }else{
                wx.miniProgram.navigateBack();
                window.close();
            }
            //cc.sys.os == cc.sys.OS_ANDROID
            //window.history.back();
            //window.close();
        });
        /*
        cc.log(cc.zc.INFO[0].voice_url);
        if (cc.zc.INFO[0].voice_url != null) {
            cc.log('xxx');
            //方法2
            cc.zc.audio_mgr.playNetSFX(cc.zc.INFO[0].voice_url);
        }
        */

    },
    // update (dt) {},
});
