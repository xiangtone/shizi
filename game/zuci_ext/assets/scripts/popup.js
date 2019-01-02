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

    onLoad () {
        
    },
    on_btn_exit_click(){
        //关闭窗口
        // cc.director.end();
        cc.log("游戏退出",cc.sys.os,cc.sys.OS_WINDOWS);
        if(cc.sys.os ==cc.sys.OS_WINDOWS){
            
            window.close();
            window.history.back();
        }else{
            wx.miniProgram.navigateBack();
            //window.close();
        }
        
    },
    start () {
        //cc.log("弹出框展示-->",this.node.getChildByName('img_light_block'));
        //获取背光图片
        var light = this.node.getChildByName('img_light_block');
        //旋转图片
        var repeat = cc.repeatForever(cc.rotateBy(3.0, 360));//一直选装
        light.runAction(repeat);
    },

    // update (dt) {},
});
