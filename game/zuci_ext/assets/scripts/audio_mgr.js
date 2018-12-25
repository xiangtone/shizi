cc.Class({
    extends: cc.Component,

    properties: {
        // foo: {
        //    default: null,      // The default value will be used only when the component attaching
        //                           to a node for the first time
        //    url: cc.Texture2D,  // optional, default is typeof default
        //    serializable: true, // optional, default is true
        //    visible: true,      // optional, default is true
        //    displayName: 'Foo', // optional
        //    readonly: false,    // optional, default is false
        // },
        // ...
        bgmVolume:1.0,
        sfxVolume:1.0,
        
        bgmAudioID:-1,
        sfxAudioId:-1,
    },

    // use this for initialization
    init: function () {
        var t = cc.sys.localStorage.getItem("bgmVolume");
        if(t != null){
            this.bgmVolume = parseFloat(t);    
        }
        
        var t = cc.sys.localStorage.getItem("sfxVolume");
        if(t != null){
            this.sfxVolume = parseFloat(t);    
        }
        
        cc.game.on(cc.game.EVENT_HIDE, function () {
            cc.log("cc.audioEngine.pauseAll");
            cc.audioEngine.pauseAll();
        });
        cc.game.on(cc.game.EVENT_SHOW, function () {
            cc.log("cc.audioEngine.resumeAll");
            cc.audioEngine.resumeAll();
        });
    },

    // called every frame, uncomment this function to activate update callback
    // update: function (dt) {

    // },
    
    getUrl:function(url){
        return cc.url.raw("resources/sounds/" + url);
    },
    //播放背景音乐
    playBGM(url){
        var audioUrl = this.getUrl(url);
        cc.log(audioUrl);
        //已经播了就先停再播
        if(this.bgmAudioID >= 0){
            cc.audioEngine.stop(this.bgmAudioID);
        }
        //循环播放
        this.bgmAudioID = cc.audioEngine.play(audioUrl,true,this.bgmVolume);
    },
    //播放特效音乐
    playSFX(url){
        var audioUrl = this.getUrl(url);
        if(this.sfxAudioId >= 0){
            cc.audioEngine.stop(this.sfxAudioId);
        }
        if(this.sfxVolume > 0){
            this.sfxAudioId = cc.audioEngine.play(audioUrl,false,this.sfxVolume);    
        }
        
    },
     //播放网络特效音乐
     playNetSFX(url){
        //cc.log(this.sfxAudioId);
        if(this.sfxAudioId >= 0){
            cc.audioEngine.stop(this.sfxAudioId);
        }
        if(this.sfxVolume > 0  ){
            
            this.sfxAudioId = cc.audioEngine.play(url, false, this.sfxVolume);
            
              
        }
        
    },
    //设置特效音量
    setSFXVolume:function(v){
        if(this.sfxVolume != v){
            cc.sys.localStorage.setItem("sfxVolume",v);
            this.sfxVolume = v;
        }
    },
    //设置背景音乐音量
    setBGMVolume:function(v,force){
        if(this.bgmAudioID >= 0){
            if(v > 0){
                cc.audioEngine.resume(this.bgmAudioID);
            }
            else{
                cc.audioEngine.pause(this.bgmAudioID);
            }
            //cc.audioEngine.setVolume(this.bgmAudioID,this.bgmVolume);
        }
        if(this.bgmVolume != v || force){
            cc.sys.localStorage.setItem("bgmVolume",v);
            this.bgmVolume = v;
            cc.audioEngine.setVolume(this.bgmAudioID,v);
        }
    },
    //全部音乐停止
    pauseAll:function(){
        cc.audioEngine.pauseAll();
    },
    //全部音乐回复
    resumeAll:function(){
        cc.audioEngine.resumeAll();
    }
});
